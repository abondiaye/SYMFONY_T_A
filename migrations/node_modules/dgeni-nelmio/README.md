# dgeni-nelmio

Dgeni processor for NemlioApiDocBundle JSON output.

## Installation

```bash
npm install --save-dev dgeni-nelmio
```

## Usage

You can use this package to generate custom API documentation from the output of
the 'api:doc:dump' command.

```bash
app/symfony api:doc:dump --format=json > api.json
```

Set up the package to load the file and generate the documentation:

```js
var Dgeni = require('dgeni');
var pkg = new Dgeni.Package('docs', [
  require('dgeni-packages/ngdoc'),
  require('dgeni-nelmio')
])
  .config(function (readFilesProcessor, writeFilesProcessor) {
    readFilesProcessor.basePath = '.';
    readFilesProcessor.sourceFiles = [
      // Path must include the area under which the API documentation should be collected
      { include: 'content/api/api.json', basePath: 'content' }
    ];

    // Set up where to write the documentation to
    writeFilesProcessor.outputFolder = 'docs';
  });

var dgeni = new Dgeni([pkg]);
dgeni.generate();
```
