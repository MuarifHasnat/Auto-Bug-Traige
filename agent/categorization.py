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


def detect_feature(text: str) -> str:
    raw_tokens = re.findall(r"[a-z0-9+#_]+", text.lower())
    tokens = set()
    for t in raw_tokens:
        tokens.add(t)
        if "_" in t:
            for sub in t.split("_"):
                if sub:
                    tokens.add(sub)

    for name, keywords in FEATURE_MAP.items():
        if any(keyword in tokens for keyword in keywords):
            return name
    return "General"


def calculate_priority(text: str) -> str:
    words = re.findall(r"[a-z0-9_]+", text.lower())
    for i, w in enumerate(words):
        if w in DANGER_WORDS:
            # Check context window of previous 2 words for negation
            window = words[max(0, i - 2) : i]
            if any(neg in NEGATION_WORDS for neg in window):
                continue
            return "High"
    return "Medium"
