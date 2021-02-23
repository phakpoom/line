```php
require dirname(__DIR__) . '/vendor/autoload.php';

class VerifyBuilder extends \Bonn\Line\MessageBuilder\AbstractBuilder
{
    public function getTextTemplates(): \Generator
    {
        yield 'foo' => 'bar';
        yield from $this->bar(); // For clean code if you have a lot of yield
    }

    public static function getScope(): string
    {
        return 'verify';
    }

    public static function getFallbackMessage()
    {
        return \Bonn\Line\MessageBuilder\Sticker::randomStickerMessage();
    }

    public static function getRegisterAcceptText(?string $name): string
    {
        return 'Welcome to fight club, wait admin approve.';
    }

    public static function getRegisterText(): string
    {
        return 'join';
    }
    
    private function bar(): \Generator
    {
        yield 'bar' => 'baz';
    }
}

// create bot
$bot = new \Bonn\Line\LineMessagingBot('_TOKEN_', '_SECRET_', new \Bonn\Line\InMemoryLineUserManager());

$bot->handleRequestWithBuilder($_SERVER['HTTP_X_LINE_SIGNATURE'], file_get_contents('php://input'), new VerifyBuilder());
```

input "foo" -> ""

input "join" -> "Welcome to fight club, wait admin approve."

#### Enable LineUser
```php
/** @var array|\Bonn\Bundle\LineBotBundle\Model\LineUserInterface[] $allUsers */
$allUsers = $lineManager->findEnabledUserFromScope('verify');

foreach ($allUsers as $user) {
    $user->setEnabled(true);

    $lineManager->save($user);
}

```
#### After enable

input "foo" -> "bar"

input "bar" -> "baz"

input "xx" -> Random sticker

Next [Active bot](https://github.com/phakpoom/line/blob/master/doc/3-Active-Bot.md)
