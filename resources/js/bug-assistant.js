function createMessageBubble(role, content, metaText = '') {
    const wrapper = document.createElement('div');
    wrapper.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';

    const bubble = document.createElement('div');
    bubble.className = role === 'user'
        ? 'max-w-[85%] rounded-2xl rounded-br-sm bg-indigo-600 px-3.5 py-2.5 text-xs text-white shadow-2xs'
        : 'max-w-[90%] rounded-2xl rounded-bl-sm border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 shadow-2xs space-y-1';

    const textEl = document.createElement('p');
    textEl.className = 'whitespace-pre-wrap leading-relaxed';
    textEl.textContent = content;
    bubble.appendChild(textEl);

    if (metaText) {
        const metaEl = document.createElement('span');
        metaEl.className = 'block text-[10px] font-mono text-slate-400';
        metaEl.textContent = metaText;
        bubble.appendChild(metaEl);
    }

    wrapper.appendChild(bubble);
    return wrapper;
}

export function initBugAssistant() {
    const panel = document.getElementById('bugAssistantPanel');
    if (!panel || panel.dataset.initialized === 'true') return;
    panel.dataset.initialized = 'true';

    const form = document.getElementById('bugAssistantForm');
    const input = document.getElementById('bugAssistantInput');
    const sendBtn = document.getElementById('bugAssistantSendBtn');
    const followUpBtn = document.getElementById('btnAssistantFollowUp');
    const testPlanBtn = document.getElementById('btnAssistantTestPlan');
    const followUpBox = document.getElementById('bugAssistantFollowUpBox');
    const testPlanBox = document.getElementById('bugAssistantTestPlanBox');
    const messagesEl = document.getElementById('bugAssistantMessages');
    const emptyEl = document.getElementById('bugAssistantEmpty');
    const typingEl = document.getElementById('bugAssistantTyping');
    const errorEl = document.getElementById('bugAssistantError');

    const endpoint = panel.dataset.endpoint;
    const followUpEndpoint = panel.dataset.followupEndpoint;
    const testPlanEndpoint = panel.dataset.testplanEndpoint;
    const bugId = panel.dataset.bugId;
    const canClarify = panel.dataset.canClarify === '1';
    const csrf = panel.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    if (!form || !input || !sendBtn || !messagesEl || !endpoint) return;

    const scrollBottom = () => {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    };
    scrollBottom();

    const setBusy = (busy, label = 'Ask Agent') => {
        input.disabled = busy;
        sendBtn.disabled = busy;
        if (followUpBtn) followUpBtn.disabled = busy;
        if (testPlanBtn) testPlanBtn.disabled = busy;
        sendBtn.textContent = busy ? 'Thinking...' : label;
        if (typingEl) typingEl.classList.toggle('hidden', !busy);
    };

    const showError = (msg) => {
        if (!errorEl) return;
        errorEl.textContent = msg;
        errorEl.classList.remove('hidden');
    };

    const clearError = () => {
        if (!errorEl) return;
        errorEl.classList.add('hidden');
        errorEl.textContent = '';
    };

    const postJson = async (url, payload = {}) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok || !data.success) {
            const validationMsg = data.errors ? Object.values(data.errors).flat()[0] : null;
            throw new Error(validationMsg || data.message || 'Assistant could not complete the request.');
        }
        return data;
    };

    const sendQuestion = async (questionText) => {
        const question = String(questionText || '').trim();
        if (!question) return;
        if (emptyEl) emptyEl.classList.add('hidden');
        clearError();
        messagesEl.appendChild(createMessageBubble('user', question));
        input.value = '';
        scrollBottom();
        setBusy(true);
        try {
            const data = await postJson(endpoint, { message: question });
            const replyText = data.message?.content || 'No response recorded.';
            const metaText = data.fallback_reason ? `Evidence fallback (${data.fallback_reason})` : 'Grounded in saved triage';
            messagesEl.appendChild(createMessageBubble('assistant', replyText, metaText));
            scrollBottom();
        } catch (err) {
            showError(err.message || 'Failed to contact the assistant.');
        } finally {
            setBusy(false);
            input.focus();
        }
    };

    const renderFollowUp = (data) => {
        if (!followUpBox) return;
        followUpBox.innerHTML = '';
        const header = document.createElement('div');
        header.className = 'flex items-center justify-between text-[11px] font-bold text-indigo-900';
        header.textContent = 'Suggested Follow-up Questions';
        followUpBox.appendChild(header);

        (data.questions || []).forEach((qItem) => {
            const card = document.createElement('div');
            card.className = 'rounded-xl border border-indigo-100 bg-indigo-50/40 p-2.5 space-y-1 text-[11px]';

            const qText = document.createElement('p');
            qText.className = 'font-semibold text-slate-900';
            qText.textContent = qItem.question;
            card.appendChild(qText);

            const reason = document.createElement('p');
            reason.className = 'text-slate-600 text-[10px]';
            reason.textContent = `${qItem.reason} (${qItem.citation})`;
            card.appendChild(reason);

            if (canClarify && bugId) {
                const clarifyForm = document.createElement('form');
                clarifyForm.method = 'POST';
                clarifyForm.action = '/api/bugs/clarifications';
                clarifyForm.className = 'pt-1';
                clarifyForm.onsubmit = () => window.confirm('Send this follow-up question as a formal clarification request to the reporter?');

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrf;

                const bugInput = document.createElement('input');
                bugInput.type = 'hidden';
                bugInput.name = 'bug_id';
                bugInput.value = bugId;

                const qInput = document.createElement('input');
                qInput.type = 'hidden';
                qInput.name = 'question';
                qInput.value = qItem.question;

                const submitClarify = document.createElement('button');
                submitClarify.type = 'submit';
                submitClarify.className = 'rounded-lg border border-indigo-300 bg-white hover:bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700 transition';
                submitClarify.textContent = 'Send as Clarification';

                clarifyForm.append(csrfInput, bugInput, qInput, submitClarify);
                card.appendChild(clarifyForm);
            }

            followUpBox.appendChild(card);
        });
        followUpBox.classList.remove('hidden');
    };

    const renderTestPlan = (data) => {
        if (!testPlanBox) return;
        testPlanBox.innerHTML = '';
        const header = document.createElement('div');
        header.className = 'text-[11px] font-bold text-emerald-900';
        header.textContent = data.title || 'Verification Test Plan';
        testPlanBox.appendChild(header);

        (data.checklist || []).forEach((item) => {
            const label = document.createElement('label');
            label.className = 'flex items-start gap-2 rounded-xl border border-emerald-100 bg-emerald-50/40 p-2.5 text-[11px] cursor-pointer';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'mt-0.5 rounded border-emerald-400 text-emerald-600';

            const body = document.createElement('div');
            body.className = 'space-y-0.5 min-w-0 flex-1';
            const stepEl = document.createElement('p');
            stepEl.className = 'font-semibold text-slate-900';
            stepEl.textContent = item.step;
            const expEl = document.createElement('p');
            expEl.className = 'text-[10px] text-slate-600';
            expEl.textContent = `Expected: ${item.expected} [${item.citation}]`;
            body.append(stepEl, expEl);

            label.append(checkbox, body);
            testPlanBox.appendChild(label);
        });
        testPlanBox.classList.remove('hidden');
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        sendQuestion(input.value);
    });

    panel.querySelectorAll('.assistant-quick-q').forEach((btn) => {
        btn.addEventListener('click', () => sendQuestion(btn.dataset.question || btn.textContent || ''));
    });

    if (followUpBtn && followUpEndpoint) {
        followUpBtn.addEventListener('click', async () => {
            if (emptyEl) emptyEl.classList.add('hidden');
            clearError();
            setBusy(true);
            try {
                const data = await postJson(followUpEndpoint);
                renderFollowUp(data);
                const metaText = data.fallback_reason ? `Evidence fallback (${data.fallback_reason})` : 'Grounded in saved triage';
                messagesEl.appendChild(createMessageBubble('assistant', data.summary_text || 'Follow-up questions generated.', metaText));
                scrollBottom();
            } catch (err) {
                showError(err.message || 'Could not generate follow-up questions.');
            } finally {
                setBusy(false);
            }
        });
    }

    if (testPlanBtn && testPlanEndpoint) {
        testPlanBtn.addEventListener('click', async () => {
            if (emptyEl) emptyEl.classList.add('hidden');
            clearError();
            setBusy(true);
            try {
                const data = await postJson(testPlanEndpoint);
                renderTestPlan(data);
                const metaText = data.fallback_reason ? `Evidence fallback (${data.fallback_reason})` : 'Grounded in saved triage';
                messagesEl.appendChild(createMessageBubble('assistant', data.summary_text || 'Test plan generated.', metaText));
                scrollBottom();
            } catch (err) {
                showError(err.message || 'Could not generate test plan.');
            } finally {
                setBusy(false);
            }
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBugAssistant);
} else {
    initBugAssistant();
}
