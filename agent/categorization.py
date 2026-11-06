import re

FEATURE_MAP = {
    "Authentication": ["login", "logout", "password", "auth", "authentication", "authorization", "session", "token"],
    "Payments": ["payment", "invoice", "billing", "discount", "transaction"],
    "Database": ["sql", "database", "query", "migration", "postgres", "mysql"],
    "API": ["api", "http", "request", "response", "endpoint", "json"],
    "Frontend": ["button", "form", "css", "react", "vue", "html", "render"],
    "Performance": ["slow", "timeout", "memory", "performance", "latency"],
}

DANGER_WORDS = [
    "crash",
    "fatal",
    "blocker",
    "broken",
    "error",
    "exception",
    "undeclared",
    "mismatch",
]


def detect_feature(text: str) -> str:
    tokens = set(re.findall(r"[a-z0-9]+", text.lower()))
    for name, keywords in FEATURE_MAP.items():
        if any(keyword in tokens for keyword in keywords):
            return name
    return "General"


def calculate_priority(text: str) -> str:
    combined_text = text.lower()
    for word in DANGER_WORDS:
        if word in combined_text:
            return "High"
    return "Medium"
