# CampusLynk AI Design Manifest
**Platform:** CampusLynk Academic Management System (AMS)  
**Brand Identity:** Connect. Manage. Empower.  
**Version:** 1.0.0 Architecture Standard  

---

## 1. Purpose & Constitution
This design manifest serves as the formal design constitution for the CampusLynk Academic Management System. It codifies the visual language, token architecture, and governance rules derived from the official brand identity system.

---

## 2. Core Architectural Principles

1. **Stability over Speed**: System predictability and zero regression take precedence over rapid bulk development. Every component and view must be verified against rigorous quality checks.
2. **Consistency over Creativity**: The design system is a locked standard. Visual uniformity across all 74+ views is paramount; bespoke, one-off visual inventions are strictly disallowed.
3. **Component Reuse over Component Creation**: Every interface must be composed exclusively of approved, frozen atomic components from `COMPONENT_FREEZE.md`. Proliferation of custom wrappers is prohibited.
4. **Incremental Migration over Bulk Migration**: Page migration must proceed strictly one page at a time. Each view undergoes complete audit, responsive testing, and functional sign-off before subsequent views are initiated.
5. **Backend Preservation over Frontend Optimization**: Presentation must never compromise backend integrity. Controller methods, route endpoints, CSRF tokens, database queries, and session state are immutable constraints.

---

## 3. Visual Architecture Standards

1. **Clarity & Calm Hierarchy (70/15/10/5 Rule)**:
   - **70% Neutral Base**: Clean canvas (`#FAFAFB`), white cards (`#FFFFFF`), subtle slate borders (`#E5E7EB`).
   - **15% Low-Emphasis Blue**: Row hover tints (`#F8FAFC`), active nav indicators (`#EEF4FF`), soft focus rings.
   - **10% Medium-Emphasis Blue**: Lucide 2px line vector icons, active text links.
   - **5% Full-Strength Blue (`#2563EB`)**: Exclusively reserved for Primary Action CTAs.
2. **Responsive Continuum**: Every screen is built as a single responsive continuum (Mobile <768px, Tablet 768px-1023px, Desktop >1024px) inheriting from standard layouts without fragmented templates.
3. **Accessibility by Default**: WCAG 2.1 AA compliant contrast, 44px minimum interactive touch targets, visible focus indicators, and minimum `14px (text-sm)` typography.
4. **Clean Solid Typography**: Crisp solid high-contrast colors (white, slate-900, slate-700, blue-600) with zero text-shadows, neon glows, or eye-straining blur effects.
