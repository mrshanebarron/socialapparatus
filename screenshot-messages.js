import { chromium } from 'playwright';

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext({
    viewport: { width: 1400, height: 900 }
  });
  const page = await context.newPage();

  // Login
  await page.goto('http://community.test/login');
  await page.fill('input[name="email"]', 'mrshanebarron@gmail.com');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');

  // Go to messages page
  await page.goto('http://community.test/messages');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-messages.png', fullPage: true });

  await browser.close();
  console.log('Screenshots saved!');
})();
