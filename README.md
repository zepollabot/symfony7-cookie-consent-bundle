# Cookie Consent bundle for Symfony

Symfony bundle to append Cookie Consent to your website to comply to AVG/GDPR for cookies. This bundle is tested for Symfony 5. The style works with Bootstrap 5. Bassed on fatalnetwork/symfony-cookie-consent-bundle

## Installation

### Step 1: Download packages

In a Symfony application run this command to install Cookie Consent bundle in your application

```bash
composer require chanondb/symfony7-cookie-consent-bundle
```

In a Symfony application run this command to install Bootstrap in your application

```bash
npm i bootstrap --save-dev
```

### Step 2: Configuration

#### /config/bundles.php

Include the bundle to your project

```php
return [
  // ...
  Chanondb\CookieConsentBundle\CBCookieConsentBundle::class => ['all' => true],
  // ...
];
```

#### /config/packages/cb_cookie_consent.yaml

Configure your Cookie Consent with the following possible settings

```yaml
cb_cookie_consent:
  categories: # Below are the default supported categories
    - 'analytics'
    - 'marketing'
    - 'preferences'
  use_logger: true # Logs user actions to database
  http_only: true # Sets HttpOnly on cookies
  form_action: $routeName # When set, xhr-Requests will only be sent to this route. Take care of having the route available.
  csrf_protection: true # The cookie consent form is csrf protected or not
  disabled_routes: ['privacy', 'imprint'] # defined controller route names where cookieConsent will not be shown by default
```

#### /config/routes/cb_cookie_consent.yaml

When not using symfony flex, enable the bundles routing manually

```yaml
cb_cookie_consent:
  resource: '@CBCookieConsentBundle/Resources/config/routing.yaml'
```

### Step 3: Assets

#### Bootstrap Style

When enabled SassLoader in your webpack.config.js add the scss file from bootstrap to your project

```scss
@import '~bootstrap/scss/bootstrap.scss';
```

#### Stimulus Controller

Copy the stimulus controller from Resources/assets/controllers/cookie_consent_modal_controller.js to your controllers folder inside your application (usually /assets/controllers/cookie_consent_modal_controller.js)

#### JS and CSS rebuild

When you set new js oder style (css or scss) files you have to make a rebuild of your public assets

```bash
npm run dev
```

### Step 4: Database

When using logger you have to make a migration file

```bash
php bin/console make:migration
```

After that you can migrate the changes

```bash
php bin/console doctrine:migration:migrate
```

## Usage

### Twig implementation

Load the cookie consent in Twig via render_esi ( to prevent caching ) at any place you like

```twig
{{ render_esi(path('cb_cookie_consent.show', {
    route: app.request.attributes.get('_route')
})) }}
{{ render_esi(path('cb_cookie_consent.show_if_cookie_consent_not_set')) }}
```

If you want to load the cookie consent with a specific locale you can pass the locale as a parameter

```twig
{{ render_esi(path('cb_cookie_consent.show', {
  locale: 'en',
  route: app.request.attributes.get('_route')
})) }}
{{ render_esi(path('cb_cookie_consent.show_if_cookie_consent_not_set', {
  locale: app.request.locale
})) }}
```

Instead of using render_esi() you can use the render() function

```twig
{{ render(path('cb_cookie_consent.show', {
    route: app.request.attributes.get('_route')
})) }}
{{ render(path('cb_cookie_consent.show_if_cookie_consent_not_set')) }}
```

### Stimulus implementation

The stimulus controller needs to implement eg. base.html.twig, make sure the modal is inner this controller

```twig
<div id="root" {{ stimulus_controller('cookie_consent_modal') }}>
  {# ... #}
</div>
```

You can set more links for opening the modal like this

```twig
<a href="#" {{ stimulus_action('cookie_consent_modal', 'show') }}>Cookies</a>
```

### Cookies

When a user submits the form the preferences are saved as cookies. The cookies have a lifetime of 1 year. The following cookies are saved

- **Cookie_Consent**: date of submit
- **Cookie_Consent_Key**: Generated key as identifier to the submitted Cookie Consent of the user
- **Cookie*Category*[CATEGORY]**: selected value of user (_true_ or _false_)

### Logging

AVG/GDPR requires all given cookie preferences of users to be explainable by the webmasters. For this we log all cookie preferences to the database. IP addresses are anonymized. This option can be disabled in the config.

![Log](https://raw.githubusercontent.com/fatalnetwork/symfony-cookie-consent-bundle/master/Resources/doc/log.png)

### Themes

![View](https://raw.githubusercontent.com/fatalnetwork/symfony-cookie-consent-bundle/master/Resources/doc/view.png)

### TwigExtension

The following TwigExtension functions are available

**fncookieconsent_isCategoryAllowedByUser**
check if user has given it's permission for certain cookie categories (return bool)

```twig
{% if fncookieconsent_isCategoryAllowedByUser('analytics') == true %}
    ...
{% endif %}
```

**fncookieconsent_isCookieConsentSavedByUser**
check if user has saved any cookie preferences (return bool)

```twig
{% if fncookieconsent_isCookieConsentSavedByUser() == true %}
    ...
{% endif %}
```

**fncookieconsent_isCookieConsentOpenByDefault**
check if the modal view has to open for the user (return string)

```twig
{% if fncookieconsent_isCookieConsentOpenByDefault(string current_route, array disabled_routes) == 'true' %}
    ...
{% endif %}
```

## Customization

### Categories

You can add or remove any category by changing the config and making sure there are translations available for these categories.

### Translations

All texts can be altered via Symfony translations by overwriting the CBCookieConsentBundle translation files. Use the privacy_route variable inside language files to define the definied route names inside your controller.

### Styling

CBCookieConsentBundle comes without an own styling, it is styled from bootstrap. A sass file is available in Resources/assets/css/cookie_consent.scss and a build css file is available in Resources/public/css/cookie_consent.css for customization. Each element has a unique class name, so you can change everything.

#### Option SASS

Copy the file Resources/assets/css/cookie_consent.scss into your project style folder, eg. /assets/styles/cookie_consent.scss and include this scss file into your /assets/styles/app.scss

```scss
@import './cookie_consent.scss';
```

#### Option CSS

Include the stylesheet in your template, eg. /templates/base.html.twig

```twig
{% include "@CBCookieConsent/cookie_consent_styling.html.twig" %}
```

### Javascript

By loading Resources/public/js/cookie_consent.js the cookie consent will be submitted via ajax.

### Events

When a form button is clicked, the event of cookie-consent-form-submit-successful is created. Use the following code to listen to the event and add your custom functionality.

```javascript
document.addEventListener(
  'cookie-consent-form-submit-successful',
  function (e) {
    // ... your functionality
    // ... e.detail is available to see which button is clicked.
  },
  false,
)
```

### Template Themes

You can override the templates by placing templates inside your project (except for Symfony 5 projects):

```twig
# app/Resources/CBCookieConsentBundle/views/cookie_consent.html.twig
{% extends '@!CBCookieConsent/cookie_consent.html.twig' %}

{% block title %}
    Your custom title
{% endblock %}
```

#### Template override for Symfony 5 projects

You can override the templates by placing templaces inside you project as below. Be careful, it is important to place templates at this location: "app/templates/bundles/CBCookieConsentBundle/" .

```twig
# app/templates/bundles/CBCookieConsentBundle/cookie_consent.html.twig
{% extends '@!CBCookieConsent/cookie_consent.html.twig' %}

{% block intro %}
    Your custom intro
{% endblock %}
```
