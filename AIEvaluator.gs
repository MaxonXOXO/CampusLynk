// ============================================================================
// CARMEL LINX - GEMINI AI EVALUATION MODULE (AIEvaluator.js)
// ============================================================================

/**
 * Evaluates a descriptive answer against the correct answer key
 * Integrates with Google Gemini API when API key is set, else runs offline fallback
 */
function evaluateAnswerWithAI(questionText, correctAnswerKey, studentAnswer) {
  try {
    let apiKey = "";
    
    // Attempt to read Google Apps Script Script Properties
    try {
      if (typeof PropertiesService !== "undefined") {
        apiKey = PropertiesService.getScriptProperties().getProperty("GEMINI_API_KEY");
      }
    } catch(e) {
      // Fallback to local simulator
    }
    
    if (apiKey) {
      const prompt = `You are an academic examiner grading engineering diploma students. 
Evaluate the student's answer based on the provided correct answer key. Give a score out of 10.

Question: "${questionText}"
Correct Answer Key: "${correctAnswerKey}"
Student Answer: "${studentAnswer}"

Evaluate strictly based on engineering correctness.
Provide your response in JSON format ONLY:
{
  "marks": <score out of 10 (integer)>,
  "feedback": "<concise 1-2 sentence constructive feedback highlighting what is correct and what was missed>"
}
Do not wrap in markdown \`\`\`json blocks. Return raw JSON text only.`;

      const url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" + apiKey;
      
      const payload = {
        contents: [{
          parts: [{ text: prompt }]
        }],
        generationConfig: {
          responseMimeType: "application/json"
        }
      };
      
      const options = {
        method: "post",
        contentType: "application/json",
        payload: JSON.stringify(payload),
        muteHttpExceptions: true
      };
      
      // Call GAS HTTP fetch
      const response = UrlFetchApp.fetch(url, options);
      const responseCode = response.getResponseCode();
      const responseText = response.getContentText();
      
      if (responseCode === 200) {
        const json = JSON.parse(responseText);
        const resultText = json.candidates[0].content.parts[0].text.trim();
        return JSON.parse(resultText);
      } else {
        throw new Error("Gemini API returned code " + responseCode + ": " + responseText);
      }
    } else {
      // Offline fallback: Keyword heuristic matching for Carmel Linx Simulator
      const cleanStudent = studentAnswer.toLowerCase().trim();
      if (cleanStudent.length < 10) {
        return {
          marks: 0,
          feedback: "AI Evaluation: Answer is too brief or empty. Zero marks assigned."
        };
      }
      
      // Look for common keywords in answer key and student answer
      const keyWords = correctAnswerKey.toLowerCase().split(/\s+/).filter(w => w.length > 4);
      let matchCount = 0;
      
      keyWords.forEach(word => {
        const cleanWord = word.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,"");
        if (cleanWord.length > 3 && cleanStudent.includes(cleanWord)) {
          matchCount++;
        }
      });
      
      const matchRatio = keyWords.length > 0 ? matchCount / keyWords.length : 0;
      let score = Math.floor(matchRatio * 10) + 1;
      
      // Cap score at 10
      if (score > 10) score = 10;
      if (score < 2) score = 2; // give basic attempt marks if length is decent
      
      let feedback = "";
      if (score >= 8) {
        feedback = "AI Evaluation: Excellent answer. The key engineering concepts are correctly explained.";
      } else if (score >= 5) {
        feedback = "AI Evaluation: Decent attempt. Some key details or limitations were omitted from the explanation.";
      } else {
        feedback = "AI Evaluation: Needs improvement. Core keywords and technical terminology are missing.";
      }
      
      return {
        marks: score,
        feedback: feedback
      };
    }
  } catch(e) {
    Logger.log("AI Evaluation failed, falling back. Error: " + e.toString());
    return {
      marks: 5,
      feedback: "AI system error. Automatically assigned standard attempt score (5/10). Error: " + e.toString()
    };
  }
}

/**
 * Uses Gemini AI to generate exam questions (MCQ or Descriptive) directly from syllabus text,
 * mapping each to a Course Outcome (CO1-4) with answer keys/benchmarks.
 */
function generateQuestionsFromSyllabusAI(subjectCode, syllabusText, count, type, marks) {
  try {
    let apiKey = "";
    try {
      if (typeof PropertiesService !== "undefined") {
        apiKey = PropertiesService.getScriptProperties().getProperty("GEMINI_API_KEY");
      }
    } catch(e) {}
    
    const targetMarks = marks ? marks.toString() : (type === "MCQ" ? "2" : "5");
    
    if (!apiKey) {
      // Offline fallback: generate mock questions based on text keywords
      return { status: "SUCCESS", questions: getOfflineMockQuestions(subjectCode, syllabusText, count, type, targetMarks) };
    }
    
    const countInt = parseInt(count) || 5;
    const prompt = `You are an expert curriculum examiner. Based on the following syllabus text, generate exactly ${countInt} high-quality exam questions of type "${type}" (either "MCQ" or "Descriptive") for the subject "${subjectCode}", all of which should be worth exactly ${targetMarks} marks.
    
    Syllabus content:
    "${syllabusText}"
    
    Guidelines:
    1. Distribute questions appropriately across Course Outcomes: CO1 (Knowledge/Recall), CO2 (Analysis/Application), CO3 (Design/Derivation), and CO4 (Implementation/Testing). Tag each question with "CO1", "CO2", "CO3", or "CO4" in the "CO_Tag" field.
    2. For type "MCQ", generate exactly 4 options and set "Correct_Answer" as a single letter "A", "B", "C", or "D". Set "Options" as a JSON array of strings containing the option texts.
    3. For type "Descriptive", "Options" should be an empty array, and "Correct_Answer" must contain the correct model answer reference key / evaluation benchmarks.
    4. Provide your response in JSON format ONLY as a root-level JSON array of objects:
    [
      {
        "Subject_Code": "${subjectCode}",
        "Type": "${type}",
        "CO_Tag": "<CO1/CO2/CO3/CO4>",
        "Question_Text": "<question text>",
        "Options": ["Option A text", "Option B text", "Option C text", "Option D text"],
        "Correct_Answer": "<correct answer key / model answer>",
        "Marks": "${targetMarks}"
      }
    ]
    Do not wrap in markdown \`\`\`json blocks. Return raw JSON text only.`;

    const url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" + apiKey;
    const payload = {
      contents: [{ parts: [{ text: prompt }] }],
      generationConfig: { responseMimeType: "application/json" }
    };
    
    const options = {
      method: "post",
      contentType: "application/json",
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    };
    
    const response = UrlFetchApp.fetch(url, options);
    if (response.getResponseCode() === 200) {
      const resultText = JSON.parse(response.getContentText()).candidates[0].content.parts[0].text.trim();
      let parsed = JSON.parse(resultText);
      // Double check it is an array
      if (!Array.isArray(parsed)) {
        if (parsed.questions && Array.isArray(parsed.questions)) {
          parsed = parsed.questions;
        } else {
          parsed = [parsed];
        }
      }
      return { status: "SUCCESS", questions: parsed };
    } else {
      throw new Error("Gemini AI API returned error: " + response.getContentText());
    }
  } catch (err) {
    Logger.log("generateQuestionsFromSyllabusAI failed: " + err.toString());
    const targetMarks = marks ? marks.toString() : (type === "MCQ" ? "2" : "5");
    return { status: "SUCCESS", message: err.toString(), questions: getOfflineMockQuestions(subjectCode, syllabusText, count, type, targetMarks) };
  }
}

/**
 * Uses Gemini AI to parse a messy, unformatted copy-pasted block of questions from old PDF or Word exam sheets,
 * converting them into clean question database objects.
 */
function extractQuestionsFromTextAI(subjectCode, rawText) {
  try {
    let apiKey = "";
    try {
      if (typeof PropertiesService !== "undefined") {
        apiKey = PropertiesService.getScriptProperties().getProperty("GEMINI_API_KEY");
      }
    } catch(e) {}
    
    if (!apiKey) {
      return { status: "ERROR", message: "Gemini API key not configured in Script Properties." };
    }
    
    const prompt = `You are a data entry assistant. Parse the following unformatted copy-pasted text from an exam paper/syllabus and extract all individual questions. Format them for our database for the subject "${subjectCode}".
    
    Copy-pasted text:
    "${rawText}"
    
    Guidelines:
    1. Identify if a question is Multiple Choice (MCQ) or Descriptive. Tag "Type" as "MCQ" or "Descriptive".
    2. Try to classify each question into a Course Outcome: CO1, CO2, CO3, or CO4 based on complexity. Set "CO_Tag" accordingly. If unknown, set "CO1".
    3. For MCQ, extract the options and correct answer letter (A, B, C, or D). Set "Options" as a JSON array of strings containing the option texts.
    4. For Descriptive, set "Options" as an empty array, and summarize a model benchmark answer or correct reference answer in "Correct_Answer".
    5. Determine the marks weightage (e.g. 2, 5, or 10 marks) based on any inline text cues (like "[2 marks]", "(5)", "Part A", etc.) or question complexity. If not specified, default MCQ to 2 marks and Descriptive to 5 marks. Store this as a string in the "Marks" field.
    6. Provide your response in JSON format ONLY as a root-level JSON array of objects:
    [
      {
        "Subject_Code": "${subjectCode}",
        "Type": "<MCQ/Descriptive>",
        "CO_Tag": "<CO1/CO2/CO3/CO4>",
        "Question_Text": "<question text>",
        "Options": ["Option A text", "Option B text", "Option C text", "Option D text"],
        "Correct_Answer": "<correct answer key / model answer>",
        "Marks": "<2/5/10>"
      }
    ]
    Do not wrap in markdown \`\`\`json blocks. Return raw JSON text only.`;

    const url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" + apiKey;
    const payload = {
      contents: [{ parts: [{ text: prompt }] }],
      generationConfig: { responseMimeType: "application/json" }
    };
    
    const options = {
      method: "post",
      contentType: "application/json",
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    };
    
    const response = UrlFetchApp.fetch(url, options);
    if (response.getResponseCode() === 200) {
      const resultText = JSON.parse(response.getContentText()).candidates[0].content.parts[0].text.trim();
      let parsed = JSON.parse(resultText);
      if (!Array.isArray(parsed)) {
        if (parsed.questions && Array.isArray(parsed.questions)) {
          parsed = parsed.questions;
        } else {
          parsed = [parsed];
        }
      }
      return { status: "SUCCESS", questions: parsed };
    } else {
      throw new Error("Gemini AI API returned error: " + response.getContentText());
    }
  } catch (err) {
    Logger.log("extractQuestionsFromTextAI failed: " + err.toString());
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fallback generator if API key is not present (Carmel Linx Simulator Mode)
 */
function getOfflineMockQuestions(subjectCode, syllabusText, count, type, marks) {
  const countInt = parseInt(count) || 5;
  const questions = [];
  const cos = ["CO1", "CO2", "CO3", "CO4"];
  const marksVal = marks ? marks.toString() : (type === "MCQ" ? "2" : "5");
  
  // Extract some keywords from syllabus
  const keywords = syllabusText.split(/\s+/).filter(w => w.length > 5).slice(0, 10);
  const subjectTopic = keywords.length > 0 ? keywords[0] : "Engineering Core Concept";
  
  for (let i = 1; i <= countInt; i++) {
    const co = cos[(i - 1) % 4];
    if (type === "MCQ") {
      questions.push({
        Subject_Code: subjectCode,
        Type: "MCQ",
        CO_Tag: co,
        Question_Text: `Identify the correct specification/term regarding "${subjectTopic} topic part ${i}"? (Offline fallback)`,
        Options: [`Primary configuration of ${subjectTopic}`, `Alternative method for ${subjectTopic}`, `Standard compliance indicator`, `None of the listed options`],
        Correct_Answer: "A",
        Marks: marksVal
      });
    } else {
      questions.push({
        Subject_Code: subjectCode,
        Type: "Descriptive",
        CO_Tag: co,
        Question_Text: `Explain in detail the fundamental principles, construction, and operation of "${subjectTopic} block ${i}". (Offline fallback)`,
        Options: [],
        Correct_Answer: `The student must outline the working principles of ${subjectTopic}. Score based on key phrases: ${subjectTopic}, operation, construction, application.`,
        Marks: marksVal
      });
    }
  }
  return questions;
}
