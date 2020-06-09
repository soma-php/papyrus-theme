# Papyrus Starter Theme

## Installation

### As is

Simply require the theme in your Papyrus project:

```sh
composer require soma/papyrus-theme
```

### For development

The theme is built using [parcel](https://parceljs.org/). Install all modules using npm/yarn:

```sh
npm install
```

Then simply run `npm run dev` for a development build that updates on file changes. Or run `npm run release` in order to build it for production.

## Configuration

The build system is using parcel.js and relative paths to theme assets - as you can see in the commands specified in the package.json file. You might want to change the public urls to fit your environment and setup.

## License

MIT
