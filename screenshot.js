import { chromium } from 'playwright';

(async () => {
    const browser = await chromium.launch({ headless: true });
    
    // Mobile viewport (iPhone 14 Pro)
    const context = await browser.newContext({
        viewport: { width: 393, height: 852 },
        deviceScaleFactor: 3,
        isMobile: true,
        hasTouch: true
    });
    const page = await context.newPage();

    // Login
    await page.goto('http://community.test/login');
    await page.fill('input[name="email"]', 'mrshanebarron@gmail.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/dashboard', { timeout: 10000 });
    await page.waitForTimeout(1000);

    // Enable dark mode
    await page.evaluate(() => {
        localStorage.setItem('theme', 'dark');
        document.documentElement.classList.add('dark');
    });
    await page.waitForTimeout(300);

    const pages = [
        { name: 'mobile-dashboard', url: '/dashboard' },
        { name: 'mobile-feed', url: '/feed' },
        { name: 'mobile-friends', url: '/friends' },
        { name: 'mobile-groups', url: '/groups' },
        { name: 'mobile-messages', url: '/messages' },
        { name: 'mobile-profile', url: '/profile/1' },
    ];

    for (const p of pages) {
        try {
            await page.goto('http://community.test' + p.url, { timeout: 15000 });
            await page.waitForTimeout(1500);
            await page.screenshot({ path: `screenshots/${p.name}.png`, fullPage: false });
            console.log(`Captured: ${p.name}`);
        } catch (e) {
            console.log(`Error on ${p.name}: ${e.message}`);
        }
    }

    await browser.close();
    console.log('Done!');
})();
