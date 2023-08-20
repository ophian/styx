# Serendipity Styx - A reliable, secure & extensible PHP blog

[Serendipity Styx](https://ophian.github.io/) is a PHP-powered weblog engine which gives the user an easy way to maintain a Blog. While the default package is designed for the casual blogger, Serendipity offers an expandable framework with the power for professional applications.

<figure markdown="1">
  <picture>
    <source type="image/avif" srcset="https://ophian.github.io/i/v/styx_example_blog.avif">
    <source type="image/webp" srcset="https://ophian.github.io/i/v/styx_example_blog.webp">
    <img src="https://ophian.github.io/i/v/styx_example_blog.png" title="Standard Pure Theme w/ automode" alt="example blog preview">
  </picture>
  <figcaption>Serendipity Styx Example</figcaption>
</figure>

### Why Styx?
Its project name originally originates from *"River Styx"* in Greek classical mythology. Make your mind why this was chosen.
If this is too dark for you, you may read this as **S**erendipi**ty** Ne**x**t. :) or just **Sty x**.

By the time the real Maintainer began to silently retreat, *Serendipity s9y 2.1-Alpha ++* more and more became a broken playground, simplifying things without need and commits without care and appreciation about its deepest nuances. Which I regret deeply.

**Styx** is what **Serendipity** should be!

After many years of heavy core, in special for the 1.7, 2.0 and 2.1 Series, and plugin development and long years of continuously help in the community, I came to the conclusion to stop my contributes for a while - "finish" the draft of the 2.1 HTML book - present it as a birthday and farewell present to the *Serendipians* - in Spring 2016 - saw where the next S9y-Camp headed to - and realized that my expertise and insight wasn't really wanted any more. Some long month later I decided to go. Now - *21st of September* - it is done. **Alea iacta est!**

### The Serendipity Styx Edition

It was built in my origin intent primarily for myself to keep track on my vision of what Serendipity used to be, wide and open, and with multi levels of extended properties. I wanted it to not cut off this extendibility without real need. It is a contributed document of my deep affinity with Serendipity.

Serendipity Styx has strongly developed and has seen various releases since then. Previously one could say to just drop Styx over an old S9y Origin to run the internal upgrades without fear. But that was years ago and up with Serendipity Styx 3.0 in May 2020, easy migrations started to become a little bit more difficult. Go and read the <strong>Get Styx</strong> [migration](https://ophian.github.io/hc/en/installation.html#user-content-the-important-upgraders-howto---step-by-step-guide) guide to see how this can be easily done without too much effort. Even more you may want to read the [commit history](https://github.com/ophian/styx/commits/master) and/or the <strong>Styx</strong> [ChangeLog](https://github.com/ophian/styx/blob/master/docs/NEWS).

Since **plugins** are an essential part of Serendipity, this repository holds a strongly maintained additional_plugins repository. The Styx Spartacus Edition is able to work with it - Serendipity s9y origin is not. Please visit my [Blog](https://ophian.github.io/blog/) here to get all the information you need. The official Styx information and Blog site is presented here: [Styx home site](https://ophian.github.io/).

This new site for **Serendipity Styx** includes the german [Serendipity Buch](https://ophian.github.io/book/), in a brand new year 2022 revision!

Regards Ian,

_September 23, 2016_

## Installation

On most hosters, everything needed to run Serendipity should already be installed. If you install it on your custom server, install PHP >= 7.4, MySQL/MariaDB, PostgreSQL or SQLite, and Apache. ImageMagick is also useful. Upload the files from [a release archive](https://github.com/ophian/styx/releases) to your webroot and visit your URL to start the installer. 

For more details, please consult [the manual](https://ophian.github.io/hc/en/installation.html#docs-install-the-easy-way).

Recommendations are *PHP 8.2+*, *ImageMagick 7.1+*, *MariaDB 10.6+*

## Features

By default, Serendipity Styx includes:

 1. An editor to write blog entries recommended use of RichText Editor or all variants of PlainText Editors w/ markdown etc
 2. Support for trackbacks and pingbacks
 3. A media library to upload images, videos and other files and add them into entries, supporting WebP and AVIF as image variants for picture element containers
 4. Integrated anti-spam measures
 5. A collection of themes that can be selected in the backend
 6. A plugin management interface for local and remote (SPARTACUS)
 7. Categories that can be applied to written blog entries
 8. Groups and user management
 9. A backend with optional dark mode as well as for the standard frontend theme (pure)

Via plugins, additional functionality can easily be added. Some popular plugins add

 1. Support for static pages, giving your site CMS features
 2. Additional anti-spam features
 3. Tags, in addition to categories
 4. Markup languages like Markdown and Textile and so on

Plugins can be added in the backend plugin interface without the need to manually upload files.

## Support

The website contains helpful [documentation](https://ophian.github.io/hc/) that might answer your questions.

Please visit [the forums](https://github.com/ophian/styx/discussions) for additional questions and discussions. Having trouble or found a bug you can also [file an issue](https://github.com/ophian/styx).

## Development

Serendipity Styx is developed by Ian Styx. Serendipity has alway focused staying backwards compatible. Though major versions do cut with elder requirements and shifts to use the better defaults of today. If you want to contribute changes, you can send in a pull request and we will work with you to bring the changes into the software if possible. After enough trust we might offer quick team memberships.

If you want to request features, you can open a thread [in the GitHub discussion channel](https://github.com/ophian/styx/discussions). Much of the development of Serendipity is user driven, feature requests are welcome.

The [license of this project](https://github.com/ophian/styx/blob/master/LICENSE) is the BSD 3-Clause license. It's a permissive license allowing free usage of the code and derived projects.
