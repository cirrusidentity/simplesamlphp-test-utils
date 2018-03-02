[![Build Status](https://travis-ci.org/cirrusidentity/simplesamlphp-test-utils.svg?branch=master)](https://travis-ci.org/cirrusidentity/simplesamlphp-test-utils)

# simplesamlphp-test-utils
Utilities to aid in testing SimpleSAMLphp modules

# Installation

Install as a dev dependency using composer

    composer require --dev  cirrusidentity/simplesamlphp-test-utils:dev-master
    
Update the dependency

    composer update -dev  cirrusidentity/simplesamlphp-test-utils:dev-master
    
# Usage

This project makes heavy use of `AspectMock` to make SSP's internal easier to test.
Adjust your phpunit bootstrap.php per https://github.com/Codeception/AspectMock to setup AspectMock
and also ensure you set `backupGlobals="false"` in phpunit.xml

You can sanity check your project by calling `SanityChecker::confirmAspectMockConfigured()`
in a test. See `AspectMockConfiguredTest` for an example.


## Clearing Static State


You *should* reset `AspectMock` after each test.

```php
use AspectMock\Test as test;
...
    protected function tearDown() {
        test::clean(); // remove all registered test doubles
    }
```

Several SSP components also cache things across all tests. Some of these classes are
marked with the `\SimpleSAML\Utils\ClearableState` interface and you can clear this state
at the appropriate time for your tests.

## Mock Redirects

Your module may need to redirect the user somewhere. If you try to create a unit test
for a redirect you'll normally have trouble since SSP's redirect method eventually calls `exit`
You can use `MockHttp` (which internally uses `AspectMock`) to change the behavior of the redirect method
to throw an exception instead. You can then catch the exception and asserted the correct URL
was being redirect to

```php
        // Enable throwing an exception when redirects would normally be called.
        MockHttp::throwOnRedirectTrustedURL();
        $params = [
            'state' => '1234'
        ];
        try {
            HTTP::redirectTrustedURL('http://my.url.com', $params);
            $this->fail('Exception expected');
        } catch (RedirectException $e) {
            $this->assertEquals('redirectTrustedURL', $e->getMessage());
            $this->assertEquals('http://my.url.com', $e->getUrl());
            $this->assertEquals($params, $e->getParams());
        }
```

## Mock Auth Sources

See MockAuthSourceTest.
`bootstrap.php` needs to explicitly load the Source.php file

    $kernel->loadFile($projectRoot . '/vendor/simplesamlphp/simplesamlphp/lib/SimpleSAML/Auth/Source.php');
