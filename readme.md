## запускаем контейнер с Selenium + Chrome

docker run -d -p 4444:4444 --name chrome selenium/standalone-chrome



## загружаем список прокси, например:

c http://foxtools.ru/Proxy

var ips = $('table tr td:nth-child(2)');
var ports = $('table tr td:nth-child(3)');
var out = "";
ips.each(function(i, e){
	out += $(e).text() + ":" + $(ports[i]).text() + "\n";
});
console.log(out);

https://hidemy.name/ru/proxy-checker

Но лучше использовать спец сервисы:
http://sockshub.net
https://proxy6.net
https://rsocks.net
https://advanced.name



3. Пишем сценарий для Selenium. Кратко:

// CHROME options
// (see http://peter.sh/experiments/chromium-command-line-switches/)
// $options = new ChromeOptions();
// $options->addArguments(['--window-size=571,428']);
// $options->addExtensions([
//   '/path/to/chrome/extension1.crx',
//   '/path/to/chrome/extension2.crx',
// ]);
// and ChromeOptions::CAPABILITY => $options;


// main
// $this->webDriver->getTitle();
// $this->webDriver->getCurrentURL();
// $driver->navigate()->refresh();
// $driver->manage()->window()->maximize();

// $handle = $session->getWindowHandle();
// $handles = $session->getWindowHandles();
// $driver->switchTo()->window($handle);

// using the browser shortcut to create a new tab !!!
// $driver->getKeyboard()->sendKeys(
//   array(WebDriverKeys::CONTROL, 't'),
// );
// using the browser shortcut to create a new window !!!
// $driver->getKeyboard()->sendKeys(
//   array(WebDriverKeys::CONTROL, 'n'),
// );

// switching to frame and back
// $my_frame = $driver->findElement(WebDriverBy::id('my_frame'));
// $driver->switchTo()->frame($my_frame);
// $driver->switchTo()->defaultContent();


// input
// $this->webDriver->getKeyboard()->sendKeys('php-webdriver');
// $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
// $input = $driver->findElement(
//     WebDriverBy::id('q')
// );
// $input->sendKeys('php')->submit();
// or ->clear();


// WebDriverBy::className() - searches for element by its CSS class.
// WebDriverBy::cssSelector() - searches for element by its CSS selector (like jQuery).
// WebDriverBy::id() - searches for element by its id.
// WebDriverBy::linkText() - searches for a link whose visible text equals to the value provided.
// WebDriverBy::partialLinkText() - same as above, but link partly contain the value.
// WebDriverBy::tagName() - search for element by its tag name.
// WebDriverBy::xpath() - search for element by xpath. The most complex, yet, most powerful way for element location.

// find
// $firstFind = $this->webDriver->findElement(WebDriverBy::id('js-command-bar-field'));
// $firstFind->click();

// find
// $els = $this->webDriver->findElements($by);
// if (count($els)) {
//     $this->fail("Unexpectedly element was found");
// }

// text
// $text = $driver->findElement(WebDriverBy::id('signin'))->getText();

// MOUSE!!!
// $element = $driver->findElement(WebDriverBy::id('some_id'));
// $driver->getMouse()->mouseMove( $element->getCoordinates() );


// ADVANSED


// $element = $driver->findElement(WebDriverBy::id('element id'));
// if ($element->isDisplayed()) {
// }


// JS
// $session->timeouts()->async_script(['ms'=>5000]);
// $sResult = $session->executeAsyncScript($jsAsText, []);


// WAIT
// Default wait (= 30 sec)
// $driver->wait()->until(
//   WebDriverExpectedCondition::titleIs('My Page')
// );

// Wait for at most 10s and retry every 500ms if it the title is not correct.
// $driver->wait(10, 500)->until(
//   WebDriverExpectedCondition::titleIs('My Page')
// );

// upload
  // $fileInput->setFileDetector(new LocalFileDetector());
  // $fileInput->sendKeys($filePath)->submit();
