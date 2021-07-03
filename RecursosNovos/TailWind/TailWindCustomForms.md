# Tailwind custom forms

By default, Tailwind resets any browser-specific styling, but the inputs are pretty ugly.

https://tailwindcss-custom-forms.netlify.app/

Na pasta do aplicativo
```php
npm install @tailwindcss/custom-forms --save-dev
```
Então adicione ao tailwind.config.js
```php
module.exports = {
  // ...
  plugins: [
    require('@tailwindcss/custom-forms'),
  ]
}
```
Lastly, re-compile your CSS to pick up the changes made by the plugin.

## Using the plugin’s classes

The plugin provides really simple classes that you can use on each type of input. Some of the classes included are form-input, form-textarea, form-select, form-multiselect, form-checkbox, and form-radio. Add one of these to the respective input type, and instantly you’ll have better styling than the browser defaults.

To customize the background colors on checkbox and radio inputs, add a regular Tailwind text-{color} class to the input specifying the color you’d like to be applied.

You can also customize the size of checkbox and radio inputs by adding h-{height} and w-{width} classes directly on the inputs. They use h-4 w-4 by default, but if you want your input to be slightly larger, try h-5 w-5.

## Customizing the default values

Just like Tailwind, the Custom Forms plugin provides a really nice way to customize the defaults. Add a theme.customForms key to your Tailwind config file you can customize specific properties on each input type using a CSS-in-JS syntax:
```php
// tailwind.config.js
module.exports = {
  theme: {
    customForms: theme => ({
      default: {
        input: {
          borderRadius: theme('borderRadius.lg'),
          backgroundColor: theme('colors.gray.200'),
          '&:focus': {
            backgroundColor: theme('colors.white'),
          }
        },
        select: {
          borderRadius: theme('borderRadius.lg'),
          boxShadow: theme('boxShadow.default'),
        },
        checkbox: {
          width: theme('spacing.6'),
          height: theme('spacing.6'),
        },
      },
    })
  },
}
```
To read more about using and customizing the plugin, read the plugin’s documentation.

Conclusion

The Tailwind Custom Forms plugin provides a way for you to quickly get started styling your form inputs. Whether you use the plugin to prototype a page or use it in production, I think it’s a great choice.

Check out the plugin’s demo page, documentation, and source code for more information.

https://laravel-news.com/tailwindcss-custom-forms 
