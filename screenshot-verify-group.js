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

  // Go to groups page to see the created group
  await page.goto('http://community.test/groups');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-groups-with-group.png', fullPage: true });

  // Go to the Laravel Developers group
  await page.goto('http://community.test/groups/laravel-developers');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-group-detail.png', fullPage: true });

  await browser.close();
  console.log('Screenshots saved!');
})();
