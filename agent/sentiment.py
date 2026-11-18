import re


BLOCKER_TERMS = {
    "blocker", "blocked", "blocking", "outage", "production down", "service down",
    "cannot login", "can't login", "data loss", "security breach", "crash for everyone",
    "সব বন্ধ", "সার্ভিস বন্ধ", "লগইন করতে পারছি না", "ডাটা হারিয়েছে",
}
NEGATIVE_TERMS = {
    "broken", "crash", "fails", "failed", "failure", "urgent", "critical", "frustrated",
    "unusable", "error", "ভুল", "সমস্যা", "কাজ করছে না", "জরুরি", "ক্র্যাশ",
}
POSITIVE_TERMS = {"works", "working", "resolved", "fixed", "ধন্যবাদ", "সমাধান"}


def analyze_sentiment(text):
    normalized = re.sub(r"\s+", " ", text.lower()).strip()

    def _matches_term(term: str, target: str) -> bool:
        # Use regex word boundaries for clean word matching (supports non-ASCII/Bengali cleanly)
        pattern = rf"(?:\b|^){re.escape(term)}(?:\b|$)"
        return bool(re.search(pattern, target, re.IGNORECASE))

    blocker_terms = sorted(term for term in BLOCKER_TERMS if _matches_term(term, normalized))
    negative_count = sum(1 for term in NEGATIVE_TERMS if _matches_term(term, normalized))
    positive_count = sum(1 for term in POSITIVE_TERMS if _matches_term(term, normalized))
    if blocker_terms:
        sentiment = "urgent"
    elif negative_count > positive_count:
        sentiment = "negative"
    elif positive_count > negative_count:
        sentiment = "positive"
    else:
        sentiment = "neutral"
    score = max(-100, min(100, (positive_count - negative_count) * 20))
    return {
        "sentiment": sentiment,
        "sentiment_score": score,
        "blocker_signal": bool(blocker_terms),
        "blocker_terms": blocker_terms,
    }
