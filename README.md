# Mavis :octocat:
[![Latest Stable Version](https://poser.pugx.org/queued/mavis/v/stable)](https://packagist.org/packages/queued/mavis) [![Latest Unstable Version](https://poser.pugx.org/queued/mavis/v/unstable)](https://packagist.org/packages/queued/mavis) [![License](https://poser.pugx.org/queued/mavis/license)](https://packagist.org/packages/queued/mavis)

Simple PHP framework, where [Slim](http://slimframework.com) goes full-stack. :space_invader:

## Disclaimer
*Note:* This is a work in progress and should not be used (yet) for production systems. The project is currently in early alpha stage. Check back later.

## Requirements
- [Composer](https://getcomposer.org/)
- PHP __5.5.+__ _with_
  - _JSON Extension_
  - _PCRE Extension_
  - _MCrypt Extension_
- Web server running eighter
  - _Apache **2.2+**_
  - _Nginx (on it's way)_

## Installation

### Composer
Just run: ```$ composer create-project queued/mavis <dir> dev-master -o```
> Note that ```<dir>``` is where the project will be installed.

### Regular installation
If you are looking for an easier way to install Mavis, just download the latest version from [here](https://github.com/queued/mavis/releases). Extract the `.zip` file you just downloaded and deploy to your server.

## Contributing
You can find a detailed contribution guide in this [contribution](CONTRIBUTING.md) guide.

## Versioning
Mavis is maintained by using the [Semantic Versioning Specification](http://semver.org).

## License
Mavis has been released under the [MIT License](LICENSE.txt).

## Libraries
| Vendor | Library | License |
| --- | --- | --- |
| [Slim Framework](http://slimframework.com) | [CSRF](http://slimframework.com/docs/features/csrf.html) | MIT |
| [Slim Framework](http://slimframework.com) | [Flash](https://github.com/slimphp/Slim-Flash) | MIT |
| [Slim Framework](http://slimframework.com) | [HTTP-Cache](http://slimframework.com/docs/features/caching.html) | MIT |
| [Igor W.](https://github.com/igorw) | [Événement](https://github.com/igorw/evenement) | MIT |
| [Jordi Boggiano](https://github.com/Seldaek) | [Monolog](https://github.com/Seldaek/monolog)| MIT |
| [Pagekit](https://www.pagekit.com/) | [Razr](https://github.com/pyrocms/lex) | MIT |
| [Oscar Otero](https://github.com/oscarotero) | [PSR-7 Middlewares](https://github.com/oscarotero/psr7-middlewares) | MIT |
| [Alex Shelkovskiy](https://github.com/alexshelkov) | [SimpleAcl](https://github.com/alexshelkov/SimpleAcl) | New BSD |
