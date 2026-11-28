import puppeteer from 'puppeteer';
import fs from 'fs';
import path from 'path';

const OUT_DIR = path.resolve('docs/screenshots');
if (!fs.existsSync(OUT_DIR)) {
    fs.mkdirSync(OUT_DIR, { recursive: true });
}

async function capture() {
    console.log('Launching browser...');
    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const page = await browser.newPage();
    await page.setViewport({ width: 1440, height: 900, deviceScaleFactor: 2 });

    // 1. Landing page
    console.log('Capturing Landing Page...');
    await page.goto('http://127.0.0.1:8000/', { waitUntil: 'networkidle0' });
    await page.screenshot({ path: path.join(OUT_DIR, '01_landing_page.png') });

    // 2. Login Page
    console.log('Capturing Login Page...');
    await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle0' });
    await page.screenshot({ path: path.join(OUT_DIR, '02_login_page.png') });

    // Perform Login as Manager
    console.log('Logging in as Manager...');
    await page.type('input[name="email"]', 'demo-manager@example.test');
    await page.type('input[name="password"]', '12345678');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0' }),
        page.click('button[type="submit"]')
    ]);

    // 3. Manager Dashboard
    console.log('Capturing Manager Dashboard...');
    await page.goto('http://127.0.0.1:8000/dashboard', { waitUntil: 'networkidle0' });
    await new Promise(r => setTimeout(r, 1000));
    await page.screenshot({ path: path.join(OUT_DIR, '03_manager_dashboard.png') });

    // 4. Kanban Workspace
    console.log('Capturing Kanban Workspace...');
    await page.goto('http://127.0.0.1:8000/workspace', { waitUntil: 'networkidle0' });
    await new Promise(r => setTimeout(r, 1000));
    await page.screenshot({ path: path.join(OUT_DIR, '04_kanban_workspace.png') });

    // 5. AI Evaluation Lab
    console.log('Capturing AI Evaluation Lab...');
    await page.goto('http://127.0.0.1:8000/evaluation-lab', { waitUntil: 'networkidle0' });
    await new Promise(r => setTimeout(r, 1000));
    await page.screenshot({ path: path.join(OUT_DIR, '05_evaluation_lab.png') });

    // 6. AI Agent Triage Submission Page
    console.log('Capturing AI Agent Submission Page...');
    await page.goto('http://127.0.0.1:8000/ai-agent', { waitUntil: 'networkidle0' });
    await new Promise(r => setTimeout(r, 1000));
    await page.screenshot({ path: path.join(OUT_DIR, '06_ai_agent_triage.png') });

    // 7. Bug Details Page
    console.log('Capturing Bug Details Page (Bug 130)...');
    try {
        await page.goto('http://127.0.0.1:8000/bugs/130', { waitUntil: 'networkidle0' });
        await new Promise(r => setTimeout(r, 1500));
        await page.screenshot({ path: path.join(OUT_DIR, '07_bug_details.png') });
    } catch (e) {
        console.error('Failed to capture bug 130:', e);
    }

    // 8. Persistent FastAPI Microservice Swagger UI
    console.log('Capturing FastAPI Swagger UI...');
    try {
        await page.goto('http://127.0.0.1:8001/docs', { waitUntil: 'networkidle0' });
        await new Promise(r => setTimeout(r, 1500));
        await page.screenshot({ path: path.join(OUT_DIR, '08_fastapi_swagger.png') });
    } catch (e) {
        console.error('Failed to capture FastAPI docs:', e);
    }

    await browser.close();
    console.log('All screenshots captured successfully in ' + OUT_DIR);
}

capture().catch(err => {
    console.error('Capture error:', err);
    process.exit(1);
});
