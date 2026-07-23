import sys
import json
import re
from pypdf import PdfReader

def parse_syllabus(pdf_path):
    reader = PdfReader(pdf_path)
    full_text = ""
    standard_text = ""
    for page in reader.pages:
        full_text += page.extract_text(extraction_mode="layout") + "\n\n"
        standard_text += page.extract_text() + "\n\n"

    # 1. Parse Meta Details
    course_code = "1002"
    course_title = "Engineering Mathematics"
    credits_val = 4
    ltpr_val = "3:1:0:0"
    cie_marks = 40
    ese_marks = 60
    total_hours = 60

    # CIE Marks
    match_cie = re.search(r'CIE Marks\s+(\d+)', full_text, re.IGNORECASE)
    if match_cie: cie_marks = int(match_cie.group(1))

    # ESE Marks
    match_ese = re.search(r'ESE Marks\s+(\d+)', full_text, re.IGNORECASE)
    if match_ese: ese_marks = int(match_ese.group(1))

    # Credits
    match_cred = re.search(r'Credits\s+(\d+)', full_text, re.IGNORECASE)
    if match_cred: credits_val = int(match_cred.group(1))

    # Course Code
    match_code = re.search(r'Course Code\s+(\d+)', full_text, re.IGNORECASE)
    if match_code: course_code = match_code.group(1).strip()

    # Course Title
    match_title = re.search(r'Course Title\s+([^\n]+)', full_text, re.IGNORECASE)
    if match_title: course_title = match_title.group(1).strip()

    # L:T:P:R Teaching Scheme
    match_ltpr = re.search(r'Teaching Scheme\s+([\d:]+)', full_text, re.IGNORECASE)
    if match_ltpr: ltpr_val = match_ltpr.group(1).strip()

    # Contact/Instructional Hours
    match_ch = re.search(r'Contact Hours\s+(\d+)', full_text, re.IGNORECASE)
    if match_ch: total_hours = int(match_ch.group(1))

    # 2. Parse Course Outcomes
    cos = []
    co_pattern = r'^\s*(CO\d+)\s+(.*?)\s+(Remember|Understand|Apply|Analyze|Evaluate|Create)\s*$'
    current_co = None
    
    for line in full_text.split('\n'):
        # Check if line matches a CO start
        match = re.match(co_pattern, line, re.IGNORECASE)
        if match:
            if current_co:
                cos.append(current_co)
            current_co = {
                'id': match.group(1).upper(),
                'desc_parts': [match.group(2).strip()],
                'cognitive_level': match.group(3).strip()
            }
        else:
            if re.match(r'^\s*(CO-PO|COURSE|Legends|Teaching|Assessment|Syllabus|Suggested|Examination|CA\d+|Week)', line, re.IGNORECASE):
                if current_co:
                    cos.append(current_co)
                current_co = None
                continue
            if current_co and line.strip():
                desc_slice = line[8:110].strip() if len(line) > 8 else line.strip()
                if desc_slice:
                    current_co['desc_parts'].append(desc_slice)
                    
    if current_co:
        cos.append(current_co)
        
    formatted_cos = []
    for c in cos:
        desc_full = " ".join(c['desc_parts']).strip()
        desc_full = re.sub(r'\s+', ' ', desc_full)
        formatted_cos.append({
            'id': c['id'],
            'description': desc_full,
            'cognitive_level': c['cognitive_level']
        })
    cos = formatted_cos

    # 3. Parse CO-PO Matrix (11 PO columns)
    copo_matrix = {}
    matrix_lines = re.findall(r'^\s*(CO\d+)\s+([0-9\-\s]+)', standard_text, re.MULTILINE)
    for ml in matrix_lines:
        co_tag = ml[0].upper()
        # Parse the 11 PO columns
        cols = re.split(r'\s+', ml[1].strip())
        # pad to 11 POs
        padded_cols = []
        for val in cols[:11]:
            padded_cols.append(val if val in ['1', '2', '3'] else '-')
        while len(padded_cols) < 11:
            padded_cols.append('-')
        
        po_mapping = {}
        for p in range(1, 12):
            po_mapping[f"PO{p}"] = padded_cols[p-1]
        copo_matrix[co_tag] = po_mapping

    # 4. Parse Modules
    modules = []
    module_matches = re.findall(r'^Module\s+([IVX]+)\s+([^\n]+)', full_text, re.MULTILINE | re.IGNORECASE)
    for mm in module_matches:
        mod_num = mm[0].upper()
        mod_title = mm[1].strip()
        # Find instructional hours for this module if any
        # (Usually listed in "Syllabus - Major Topics" table)
        modules.append({
            'module_id': mod_num,
            'title': mod_title,
            'hours': 15 # fallback
        })

    # If modules not found, fallback
    if not modules:
        modules = [
            {'module_id': 'I', 'title': 'Matrices & Determinants', 'hours': 15},
            {'module_id': 'II', 'title': 'Trigonometry', 'hours': 15},
            {'module_id': 'III', 'title': 'Coordinate Geometry', 'hours': 15},
            {'module_id': 'IV', 'title': 'Differential Calculus', 'hours': 15}
        ]

    # 5. Parse Detailed Topics using Slicing Block Parser
    pos = full_text.find("Detailed Syllabus")
    if pos == -1:
        pos = full_text.find("Course Outline")
    
    detailed_text = full_text[pos:] if pos != -1 else full_text
    lines = detailed_text.split("\n")
    
    detailed_topics = []
    current_block = {
        'topic_lines': [],
        'slo_lines': [],
        'metadata': None
    }

    row_pattern = r'\b([LT])\s+(CO\d+)\s+(Remember|Understand|Apply|Analyze|Evaluate|Create|Remembering|Understanding|Applying|Analyzing|Evaluating|Creating)\s+(\d+)\s*$'

    def save_block():
        if not current_block['topic_lines'] and not current_block['slo_lines']:
            return
        topic_full = " ".join(current_block['topic_lines']).strip()
        slo_full = " ".join(current_block['slo_lines']).strip()
        
        topic_full = re.sub(r'\s+', ' ', topic_full)
        slo_full = re.sub(r'\s+', ' ', slo_full)
        
        meta = current_block['metadata']
        if meta:
            detailed_topics.append({
                'topic': topic_full,
                'learning_outcome': slo_full,
                'pedagogy': 'Lecture' if meta[0] == 'L' else 'Tutorial',
                'co_id': meta[1],
                'taxonomy': meta[2],
                'hours': int(meta[3])
            })
        current_block['topic_lines'] = []
        current_block['slo_lines'] = []
        current_block['metadata'] = None

    for line in lines:
        if not line.strip():
            save_block()
            continue
        
        # Skip header layout garbage lines
        if "Detailed Syllabus" in line or "Subtopic" in line or "Student Learning Outcome" in line or "Mode of" in line or "delivery" in line:
            save_block()
            continue
        if "Diploma Curriculum" in line or "Page #" in line or "CO-PO mapping" in line or "COURSE ARTICULATION" in line:
            save_block()
            continue
        if "Module" in line:
            save_block()
            continue

        # Slice bounds
        subtopic_val = line[0:47].strip() if len(line) > 0 else ""
        slo_val = line[47:108].strip() if len(line) > 47 else ""
        
        match = re.search(row_pattern, line)
        if match:
            if current_block['metadata'] is not None:
                save_block()
            current_block['metadata'] = (match.group(1).upper(), match.group(2).upper(), match.group(3), match.group(4))

        if subtopic_val:
            current_block['topic_lines'].append(subtopic_val)
        if slo_val:
            current_block['slo_lines'].append(slo_val)

    save_block()

    # Populate module content & sum hours dynamically from parsed topics
    roman_to_int = {'I': 1, 'II': 2, 'III': 3, 'IV': 4, 'V': 5, 'VI': 6}
    for mod in modules:
        mod_num = roman_to_int.get(mod['module_id'], 1)
        co_tag = f"CO{mod_num}"
        
        # Get all subtopics for this CO
        mod_topics = [t['topic'] for t in detailed_topics if t['co_id'] == co_tag]
        
        # Remove duplicates
        seen = set()
        clean_topics = []
        for mt in mod_topics:
            if mt not in seen:
                seen.add(mt)
                clean_topics.append(mt)
                
        mod['content'] = ", ".join(clean_topics) if clean_topics else "Introduction, basic concepts and foundational definitions."
        # Sum hours
        mod_hrs = sum(t['hours'] for t in detailed_topics if t['co_id'] == co_tag)
        mod['hours'] = mod_hrs if mod_hrs > 0 else 15

    # Compile the final result
    result = {
        'course_code': course_code,
        'course_title': course_title,
        'credits': credits_val,
        'teaching_scheme': ltpr_val,
        'cie_marks': cie_marks,
        'ese_marks': ese_marks,
        'total_hours': total_hours,
        'cos': cos,
        'copo_matrix': copo_matrix,
        'modules': modules,
        'detailed_topics': detailed_topics
    }
    return result

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({'status': 'ERROR', 'message': 'No PDF file path provided.'}))
        sys.exit(1)
        
    pdf_file_path = sys.argv[1]
    try:
        data = parse_syllabus(pdf_file_path)
        print(json.dumps({'status': 'SUCCESS', 'data': data}, ensure_ascii=True))
    except Exception as e:
        print(json.dumps({'status': 'ERROR', 'message': str(e)}, ensure_ascii=True))
