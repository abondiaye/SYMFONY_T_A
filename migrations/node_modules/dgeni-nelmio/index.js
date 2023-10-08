'use strict';

var path = require('canonical-path');
var Package = require('dgeni').Package;

var base = require('dgeni-packages/base');
var nunjucks = require('dgeni-packages/nunjucks');

var commandProcessor = require('./processors/commandProcessor');
var resourceProcessor = require('./processors/resourceProcessor');
var resourceCommandsProcessor = require('./processors/resourceCommandsProcessor');
var sectionProcessor = require('./processors/sectionProcessor');
var sectionDocsProcessor = require('./processors/sectionDocsProcessor');

var nelmioFileReader = require('./file-readers/nelmio');
var uriToIdTransformer = require('./services/transformers/uriToIdTransformer');

module.exports = new Package('nelmio', [base, nunjucks])

  .processor(commandProcessor.name, commandProcessor)
  .processor(resourceProcessor.name, resourceProcessor)
  .processor(resourceCommandsProcessor.name, resourceCommandsProcessor)
  .processor(sectionProcessor.name, sectionProcessor)
  .processor(sectionDocsProcessor.name, sectionDocsProcessor)

  .factory(nelmioFileReader.name, nelmioFileReader)
  .factory(uriToIdTransformer.name, uriToIdTransformer)

  .config(function (readFilesProcessor, nelmioFileReader) {
    if (!Array.isArray(readFilesProcessor.fileReaders)) {
      readFilesProcessor.fileReaders = [];
    }

    readFilesProcessor.fileReaders.push(nelmioFileReader);
  })

  .config(function (computeIdsProcessor, uriToIdTransformer) {
    computeIdsProcessor.idTemplates.push({
      docTypes: ['section', 'resource', 'command'],
      getId: uriToIdTransformer.getId,
      getAliases: uriToIdTransformer.getAlias
    });
  })

  .config(function (computePathsProcessor) {
    computePathsProcessor.pathTemplates.push({
      docTypes: ['section'],
      pathTemplate: '${area}/${name}',
      outputPathTemplate: 'partials/${area}/${name}.html'
    });

    computePathsProcessor.pathTemplates.push({
      docTypes: ['resource'],
      pathTemplate: '${area}/${name}',
      outputPathTemplate: 'partials/${area}/${name}.html'
    });

    computePathsProcessor.pathTemplates.push({
      docTypes: ['command'],
      pathTemplate: '${area}/${section}/${resource}/${id}',
      outputPathTemplate: 'partials/${area}/${section}/${resource}/${id}.html'
    });
  })

  .config(function (parseTagsProcessor, getInjectables) {
    parseTagsProcessor.tagDefinitions = parseTagsProcessor.tagDefinitions.concat(getInjectables([
      require('./tag-defs/section')
    ]));
  })

  .config(function (templateFinder, templateEngine) {
    templateFinder.templateFolders.unshift(path.resolve(__dirname, 'templates'));

    templateEngine.config.tags = {
      variableStart: '{$',
      variableEnd: '$}'
    };

    templateFinder.templatePatterns = [
      '${ doc.template }',
      '${doc.area}/${ doc.id }.template.html',
      '${doc.area}/${ doc.docType }.template.html',
      '${ doc.id }.template.html',
      '${ doc.docType }.template.html'
    ].concat(templateEngine.templatePatterns);

    templateEngine.filters.push(require('./rendering/filters/status-code-class'));
  });
