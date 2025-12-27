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

  // Go to friends page
  await page.goto('http://community.test/friends');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-friends.png', fullPage: true });

  // Go to pending requests page
  await page.goto('http://community.test/friends/requests');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-pending-requests.png', fullPage: true });

  // Go to my profile
  await page.goto('http://community.test/profile/1');
  await page.waitForLoadState('networkidle');
  await page.screenshot({ path: 'screenshot-profile-buttons.png', fullPage: true });

  await browser.close();
  console.log('Screenshots saved!');
})();
