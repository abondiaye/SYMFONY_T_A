'use strict';

var _ = require('lodash');

/**
 * @dgProcessor commandProcessor
 */
module.exports = function commandProcessor () {
  return {
    $runAfter: ['extra-docs-added'],
    $runBefore: ['computing-ids'],
    $process: function (docs) {
      docs.forEach(function (doc) {
        if (doc.docType !== 'command') return;

        doc.relativeUri = doc.uri.replace(doc.resource, '');
        doc.relativeName = doc.name.replace(doc.resource, '').replace(doc.method, '').trim();

        doc.statusCodes = doc.statusCodes && transformStatusCodes(doc.statusCodes) || [];

        doc.requirements = doc.requirements && transformParameters(doc.requirements) || [];
        doc.filters = doc.filters && transformParameters(doc.filters) || [];
        doc.parameters = doc.parameters && transformParameters(doc.parameters) || [];

        doc.response = doc.response && transformResponse(doc.response) || [];
      });

      return docs;
    }
  };
};

function transformParameters (parameters) {
  if (parameters.hasOwnProperty('_format')) {
    delete parameters._format;
  }
  
  parameters = _.map(parameters, function (parameter, name) {
    parameter.name = name;
    parameter.typeList = [parameter.dataType || '*'];
    parameter.type = {optional: false};
    
    if (parameter.hasOwnProperty('default')) {
      parameter.type.optional = true;
      parameter.defaultValue = parameter.default && parameter.default.toString();
    }
    
    if (parameter.hasOwnProperty('required')) {
      parameter.type.optional = !parameter.required;
    }
    
    return parameter;
  });
  
  return parameters.length ? parameters : null;
}

function transformStatusCodes (codes) {
  return _.map(codes, function (description, code) {
    return {code: code, description: description};
  });
}

function transformResponse (response) {
  if (response.hasOwnProperty('')) {
    response = response[''].children;
  }

  return _.map(response, function (parameter, name) {
    parameter.name = name;
    parameter.typeList = [getParameterType(parameter)];

    return parameter;
  });
}

function getParameterType (parameter) {
  switch (parameter.actualType) {
    case 'collection':
      return 'Array<' + toBaseName(parameter.subType) + '>';
    case 'model':
      return toBaseName(parameter.subType);
    case 'datetime':
      return 'Date';
    default:
      return parameter.actualType;
  }
}

function toBaseName (className) {
  var matches = /\\([^\\]+)$/.exec(className);

  return matches[1];
}
