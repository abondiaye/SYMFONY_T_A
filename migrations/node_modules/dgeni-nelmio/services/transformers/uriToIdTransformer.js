'use strict';

/**
 * @dgService uriToIdTransformer
 * @description
 * Transforms an API URI to a node id
 */
module.exports = function uriToIdTransformer () {
  var transformer = {};

  transformer.getId = function (doc) {
    var prefix, uri;
    
    switch (doc.docType) {
      case 'section':
      case 'resource':
        prefix = doc.docType;
        uri = doc.name;
        break;
      
      case 'command':
        prefix = doc.method.toLowerCase();
        uri = doc.uri;
        break;
      
      default:
        throw new ReferenceError('Unsupported docType: ' + doc.docType);
    }

    return uriToId(uri, prefix);
  };

  transformer.getAlias = function (doc) {
    return [
      transformer.getId(doc)
    ];
  };

  return transformer;

  function uriToId (uri, prefix) {
    if (uri.charAt(0) === '/') {
      uri = uri.substr(1);
    }

    var id = uri
      .replace(/\//g, '.')
      .replace(/\{([^}]+)}/g, '$1');

    return prefix ? prefix + ':' + id : id;
  }
};
