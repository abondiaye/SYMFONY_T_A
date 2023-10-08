'use strict';

var _ = require('lodash');

/**
 * @dgProcessor resourceCommandsProcessor
 */
module.exports = function resourceCommandsProcessor (log, createDocMessage, aliasMap) {
  return {
    $runAfter: ['ids-computed'],
    $runBefore: ['computing-paths'],
    $process: function (docs) {
      docs = docs.filter(function (doc) {
        if (doc.docType !== 'command') {
          return true;
        }

        var matches = aliasMap.getDocs('resource:' + doc.resource);

        if (matches.length !== 1) {
          log.warn(createDocMessage('unable to find suitable resource (resource: ' + doc.resource + ', hits: ' + matches.length + ')', doc));

          return true;
        }

        var resource = matches[0];

        log.debug(createDocMessage('adding command to resource: ' + doc.resource, doc));

        Array.prototype.push.apply(resource.schema, doc.response);

        if (doc.method === 'PATCH')
          resource.actions.push(doc);
        else
          resource.commands.push(doc);

        return false;
      });

      docs.forEach(function (doc) {
        if (doc.docType !== 'resource') {
          return;
        }

        doc.schema = _.unique(doc.schema, 'name');
      });

      return docs;
    }
  }
};
