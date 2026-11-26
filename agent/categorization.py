import re

FEATURE_MAP = {
    "Authentication": ["login", "loging", "logout", "password", "auth", "authentication", "authorization", "session", "token", "signin", "singin", "signup", "singup", "logon", "credential", "register", "oauth", "jwt", "pass"],
    "Payments": ["payment", "invoice", "billing", "discount", "transaction", "checkout", "stripe", "cart", "pay", "order"],
    "Database": ["sql", "database", "query", "migration", "postgres", "mysql", "sqlite", "model", "schema", "table"],
    "API": ["api", "http", "request", "response", "endpoint", "json", "rest", "route", "controller", "fetch", "axios"],
    "Frontend": ["button", "form", "css", "react", "vue", "html", "render", "ui", "view", "component", "screen", "modal", "layout"],
    "Performance": ["slow", "timeout", "memory", "performance", "latency", "lag", "freeze"],
}

DANGER_WORDS = {
    "crash",
    "crashes",
    "crashed",
    "crashing",
    "fatal",
    "blocker",
    "broken",
    "error",
    "errors",
    "exception",
    "exceptions",
    "undeclared",
    "mismatch",
    "mismatches",
}
NEGATION_WORDS = {"no", "not", "never", "without", "dont", "didnt"}


def detect_feature_with_evidence(text: str) -> dict:
    raw_tokens = re.findall(r"[a-z0-9+#_]+", text.lower())
    tokens = set()
    for t in raw_tokens:
        tokens.add(t)
        if "_" in t:
            for sub in t.split("_"):
                if sub:
                    tokens.add(sub)

    for name, keywords in FEATURE_MAP.items():
        matched = [kw for kw in keywords if kw in tokens]
        if matched:
            return {
                "feature": name,
                "matched_keywords": matched[:5],
                "reason": f"Matched {name} keyword(s): {', '.join(matched[:5])}",
            }
    return {
        "feature": "General",
        "matched_keywords": [],
        "reason": "No domain-specific module keywords matched; classified as General.",
    }


def detect_feature(text: str) -> str:
    return detect_feature_with_evidence(text)["feature"]


def calculate_priority_with_evidence(text: str) -> dict:
    words = re.findall(r"[a-z0-9_]+", text.lower())
    matched_danger = []
    negated = []
    for i, w in enumerate(words):
        if w in DANGER_WORDS:
            window = words[max(0, i - 2) : i]
            if any(neg in NEGATION_WORDS for neg in window):
                negated.append(w)
                continue
            matched_danger.append(w)

    if matched_danger:
        unique_danger = list(dict.fromkeys(matched_danger))[:5]
        return {
            "priority": "High",
            "matched_danger_words": unique_danger,
            "negated_words": list(dict.fromkeys(negated))[:5],
            "reason": f"Detected severity signal(s): {', '.join(unique_danger)}",
        }

    reason = "No fatal/crash keywords detected; assigned Medium priority."
    if negated:
        reason = f"Severity words ({', '.join(dict.fromkeys(negated))}) were negated; assigned Medium priority."
    return {
        "priority": "Medium",
        "matched_danger_words": [],
        "negated_words": list(dict.fromkeys(negated))[:5],
        "reason": reason,
    }


def calculate_priority(text: str) -> str:
    return calculate_priority_with_evidence(text)["priority"]
