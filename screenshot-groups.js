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

  // Go to groups page
  await page.goto('http://community.test/groups');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-groups.png', fullPage: true });

  // Go to create group page
  await page.goto('http://community.test/groups/create');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-create-group.png', fullPage: true });

  // Create a test group
  await page.fill('input#name', 'Laravel Developers');
  await page.fill('textarea#description', 'A community for Laravel enthusiasts to share knowledge, tips, and best practices.');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/groups/**');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-group-show.png', fullPage: true });

  await browser.close();
  console.log('Screenshots saved!');
})();
