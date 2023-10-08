'use strict';

var _ = require('lodash');

/**
 * @dgProcessor sectionProcessor
 */
module.exports = function sectionProcessor () {
  return {
    $runAfter: ['extra-docs-added'],
    $runBefore: ['computing-ids'],
    $process: function (docs) {
      docs.forEach(function (doc) {
        if (doc.docType !== 'section') return;
        if (doc.section) return;
        
        doc.section = doc.name;
        doc.resources = [];
      });
      
      return docs;
    }
  };
};
