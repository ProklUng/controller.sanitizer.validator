# Валидация и санация запроса посредством трэйтов для Symfony-ориентированных приложений

## Установка

`composer require roklung/controller-sanitizer-validator`

## Санитайзер

С помощью [https://github.com/Waavi/Sanitizer](). В контроллере должно быть реализован метод `getSanitizingRules`,
описывающий правила санации.

```php
use Prokl\RequestValidatorSanitizer\Sanitizing\SanitizableTrait;

class SimpleController extends AbstractController
{
    use SanitizableTrait;
    
    public function action(Request $request) {
         // Санитизированный Request.
            $requestSanitized = $this->sanitizeRequest(
                $request,
                $this->getSanitizingRules()
            );

    }

   /**
     * Правила санации.
     *
     * @return array
     */
    private function getSanitizingRules(): array
    {
        return [
            'id' => 'trim|escape|strip_tags|cast:string',
            'url' => 'trim|escape|strip_tags|cast:string',
            'name' => 'trim|escape|strip_tags|cast:string',
            'email' => 'trim|escape|strip_tags|cast:string',
            'phone' => 'trim|escape|strip_tags|cast:string'
        ];
    }
}
```

## Валидатор

С помощью [https://github.com/illuminate/validation](). В контроллере должно быть реализован метод `getRules`,
описывающий правила валидации в стадарте `Illuminate Validation`.

```php
use Prokl\RequestValidatorSanitizer\Validation\Custom\CustomEmailValidatorNullable;
use Prokl\RequestValidatorSanitizer\Validation\Custom\LaravelPhoneValidator;

class SimpleController extends AbstractController
{
    use ValidateableTrait;

    public function action(Request $request) {
            $this->validateRequest(
                $request,
                $this->getRules()
            );
    }
    /**
     * Правила валидации.
     *
     * @return string[]
     */
    private function getRules() : array
    {
        return [
            'name' => 'required|string',
            'id' => 'string|nullable|min:3|max:60',
            'url' => 'string|nullable|url',
            'email' => ['nullable', new CustomEmailValidatorNullable],
            'phone' => ['required', 'string', new LaravelPhoneValidator]
        ];
    }
}
```

## Кастомные валидаторы

- `CustomEmailValidator` - Email, при помощи [https://github.com/egulias/EmailValidator]()
- `CustomEmailValidatorNullable` - Email, может быть пустым
- `CyrillicAlphaValidator` - в параметре только кириллические символы
- `CyrillicAlphaValidatorNullable` - в параметре только кириллические символы, может быть пустым
- `LaravelPhoneValidator` - телефонный номер, при помощи [https://github.com/giggsey/libphonenumber-for-php]() 
- `LaravelPhoneValidatorNullable` - телефонный номер, может быть пустым