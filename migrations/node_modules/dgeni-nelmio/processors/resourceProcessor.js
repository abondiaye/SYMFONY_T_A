'use strict';

/**
 * @dgProcessor resourceProcessor
 */
module.exports = function resourceProcessor () {
  return {
    $runAfter: ['extra-docs-added'],
    $runBefore: ['computing-ids'],
    $process: function (docs) {
      docs.forEach(function (doc) {
        if (doc.docType !== 'resource') return;
        
        doc.resource = doc.name;

        // Data schema
        doc.schema = [];

        // URL endpoints
        doc.commands = [];
        doc.actions = [];
      });
      
      return docs;
    }
  };
};
