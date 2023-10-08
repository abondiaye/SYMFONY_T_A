'use strict';

var _ = require('lodash');
var path = require('canonical-path');

/**
 * @dgService nelmioFileReader
 * @description
 * Reads JSON file that was generated using the NelmioApiDocBundle
 */
module.exports = function nelmioFileReader () {
  return {
    name: 'nelmioFileReader',
    defaultPattern: /\.json$/,
    getDocs: transformApiData
  };
};

function transformApiData (fileInfo) {
  var data = JSON.parse(fileInfo.content);
  fileInfo.content = null; // Nullify content to save memory

  var resources = {};
  var docs = _.map(data, function (commands, resourceName) {
    var resource = resourceToId(resourceName);

    resources[resource] = {content: '@ngdoc resource\n@name ' + resource, uri: resourceToName(resourceName)};

    return commands.map(function (command) {
      command.uri = command.uri.replace('.{_format}', '');
      command.content = '@ngdoc command\n@name ' + command.method + ' ' + command.uri;
      command.section = (command.section || 'other').toLowerCase();
      command.resource = resource;
      command.labels = command.tags && Object.keys(command.tags) || [];

      resources[resource].section = command.section;

      return command;
    });
  });

  docs = _.flatten(docs, true);

  _.forEach(resources, function (resource, name) {
    resource.resource = name;

    docs.push(resource);
  });

  return docs;
}

function resourceToName (resource) {
  var basename = path.basename(resource);

  if (basename.charAt(0) !== '{') return resource;

  basename = '/' + basename;

  return resource.replace(basename, '');
}

function resourceToId (resource) {
  var basename = path.basename(resource);

  resource = resource.replace(/[^a-z0-9]+/gi, '.');
  resource = resource.replace(/(^\.|\.$)/g, '');
  
  if (basename.charAt(0) !== '{') return resource;
  
  basename = basename.replace(/[^a-z0-9]/gi, '');
  
  resource = resource.replace(basename, '');
  resource = resource.replace(/(^\.|\.{2,}|\.$)/g, '');

  return resource;
}
