# WordpressBase
Base WordPress theme

## Prerequisites

* Composer [https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx]
* Node [https://nodejs.org/en/download/]
* Ruby [https://www.ruby-lang.org/en/documentation/installation/]
* Ruby scss_lint gem `gem install scss_lint`

## Setup

1. Download or clone to your machine.
2. Extract content to wp-content/themes directory of your WordPress installation.
3. Rename theme folder.
4. In theme directory on the command line run `composer install` to install package dependencies.
5. In theme directory on the command line run `npm install` to install NPM packages.
6. If using a local development environment update gulpfle.js proxy to reflect development server port or website address.
7. (optional) Use realfavicongenerator.net to generate new favicons and export zip contents to /assets/favicons/.
8. (optional) Update screenshot.png to reflect theme design at 880x660 or 387x290.

## Gulp commands
Command  | Usage
------------- | -------------
gulp watch | Activates browserSync, Watch for changes to JS, PHP and SCSS files then lint and recompile JS & CSS and refresh browser
gulp deploy | Recompile JS and CSS, optimise JS, CSS and Images, Refresh browsers via browserSync, Remove unnecessary map files 
gulp js:lint | Force lint of JS files
gulp js:compile | Force compilation of JS files
gulp css:lint | Force lint of CSS files
gulp css:compile | Force compilation of CSS files
gulp fix | Manually move fonts, favicons and images to dist directory if watch task was not active when added

## NPM commands
Command  | Usage
------------- | -------------
npm run deploy | Recompile the CSS and JS, optimise images, move misc assets and prune development node libraries
npm run package | Create a clean tarball of the theme without any development files

## Folder structure

```shell
themes/your-theme-name/     # → Root of your ahoy based theme
├── assets                  # → Front-end assets
│   ├── favicons/           # → Webpack and ESLint config
│   ├── fonts/              # → Theme fonts
│   ├── images/             # → Theme images
│   ├── js/                 # → Theme JS
│   └── scss/               # → Theme stylesheets
├── components              # → Theme PHP
│   │   ├── acf/php         # → Advanced custo fields functionalty
│   │   ├── admin.php       # → Back end customisation
│   │   ├── cpt.php         # → Custom Post type declarations
│   │   ├── email.php       # → Email functioanlity
│   │   ├── helpers.php     # → Helper functions
│   │   ├── newletter.php   # → Newsletter functioanlity
│   │   ├── reset.php       # → Theme reset 
│   │   ├── setup.php       # → Theme initlisaiton
│   │   ├── social.php      # → Social functionality
│   │   ├── theme.php       # → Theme specific functioanlity
│   │   └── woocommerce.php # → Woocommerce modifications and custom funcitoanltiy
│   ├── functions/          # → Theme wrapper, asset manifest
│   └── languages/          # → Theme translation files
├── composer.json           # → Composer dependencies and scripts
├── composer.lock           # → Composer lock file (never edit)
├── dist/                   # → Built theme assets (never edit)
├── functions.php           # → Composer autoloader, theme includes
├── index.php               # → Links to index.php template (never edit)
├── node_modules/           # → Node.js packages (never edit)
├── package.json            # → Node.js dependencies and scripts
├── screenshot.png          # → Theme screenshot for WP admin
├── style.css               # → Theme meta information
├── layouts/                # → Twig templates
│   ├── blocks/             # → Cross site block level items
│   └── partials/           # → Site specific layour parts
├── templates/              # → Theme templates
│   └── layouts/            # → Base templates
└── vendor/                 # → Composer packages (never edit)
```

## Helper functions

For full details of functions and variables examine the doc blocks in the component/functions/functions-helpers.php file

Function  | Use
------------- | -------------
get_bh | Check specified date against UK bank holiday
escape_id | Convert string into non spaced alphanumeric only format fr use in unique ID's
current_page_url | Get the current page url
get_attachment_id_from_src | Return the attachment ID of a attachment from the URL (Avoid use if possible)
get_theme_image | Returns a child theme overwritable image path
the_theme_svg | Echos a child theme overwritable SVG
get_theme_svg | Returns a child theme overwritable SVG
the_icon | Echo a SVG sprite definition
user_has_role | Check if a user has a specific role
is_role | Check if current user has a specific role
clean_site_url | Return clean site url without www. or http:// or https://
get_stripped_url | Returns a relative URL without the blog_url
force_url_http | Formats a URL formatted for external linking with http:// included
encode_string | Encodes a string into a form usable in query strings for use with email addresses
decode_string | Decodes string generated by encode_string
dlog | Debug log function, logs variables or messages with optional timestamp to debug.log file in template route
dump_hook | First function deals with interpreting and formatting single hook, not really meant to be called directly
list_hooks | When called this function will output current state of all hooks in alphabetized order. If passed string as argument it will only list hooks that have that string in name
list_hook_details | Whenever hook with this function added gets executed it will output details right in place
list_live_hooks | This will list live details on all hooks or specific hook, passed as argument
curl_fetch | Fetch data from a page with sent post data and authentication
file_get_contents_curl | cURL replacement for file_get_contents
respond_and_close | Dumps formatted json response to user and ends processing
is_json | Check to see if a string is JSON formatted
is_plugin_activated | Special check to see if certain plugins are activated (WooCommerce, ACF) in a way that can be easily maintained
repeater_walker | Walks over a ACF repeater array and calls function on the rows
get_attachment_svg | Gets a child uploaded attachment SVG from attachment ID
get_attachment_image_src | Get the source for an attachment image without needing to store to a variable each time

## Roadmap

* Source packages to handle social integration.
* Check current ecosystem for WordPress form plugins to see if we've moved path the terrible contact forms 7 era to determine if we need to do this manually.

## History

###?.?.?
This started off as a bunch of WordPress themes that were created using the twentythirteen theme.
Over time of recreating the same functionality again and again I cobbled the bets bits of multiple sites into a basic theme.

###1.0.0
Creation of basic theme.

###1.1.0
2015 updates to add functionality, update to javaScript and CSS frameworks.

###2.0.0
Updated base theme, cleaned up and refactored directory structure.
Added helper functions and structured readme.
Swapped from LESS to SCSS.
Added auto initialising ACF fields for setting up backend options page.

###2.1.0
Full update to gulp task managing.
Swap over to ES6 and NPM module includes for JavaScript.
Move from hardcoded to ACF local folders.

###2.2.0
Cleaned up function file names.
Fixed external hyperlink issues in JS.
Removed newsletter and social functionality to be replaces with site specific packages.
General typo and doc block fixes.
Added missing Utility ACF group.
Moved WordPress template hierarchy files into templates directly.
move partials from components/parts/ to templates/parts/.
Updated handling of favicons to render content from theme on website root URLs.
Setup deployment NPM scripts.
Setup PSR-2 code formatting for PHP and refactored code to consistent style.
Moved admin dashboard and admin bar removal content into reset.
Created setup file for theme setup functions.

## Credits

Chris morris [http://codecomposer.co.uk]

## License

The MIT License (MIT)

Copyright (c) 2017 Chris Morris

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
