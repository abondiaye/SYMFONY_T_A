var _ = require('lodash');

/**
 * @dgProcessor sectionDocsProcessor
 * @description
 * Compute the various fields for modules
 */
module.exports = function sectionDocsProcessor (log, aliasMap, createDocMessage) {
  return {
    $runAfter: ['ids-computed'],
    $runBefore: ['computing-paths'],
    $process: function (docs) {
      docs.forEach(function (doc) {
        if (doc.docType !== 'resource') return;
        if (!doc.section) return;
        
        var matchingSections = aliasMap.getDocs('section:' + doc.section);
        
        if (matchingSections.length === 1) {
          var section = matchingSections[0];
          
          if (section.docType === 'section') {
            section.resources.push(doc);
          } else {
            throw new Error(createDocMessage('"' + section.name + '" is not a section. It is documented as "' + section.docType + '". Either the section is incorrectly typed or the section reference is invalid', doc));
          }

          doc.sectionDoc = section;
        } else if (matchingSections.length > 1) {
          var error = createDocMessage('Ambiguous section reference: "' + doc.section + '"', doc);
          error += '\nMatching sections:\n';
          _.forEach(matchingSections, function (mod) {
            error += '- ' + mod.id + '\n';
          });
          throw new Error(error);
        }
      });

      return docs;
    }
  };
};
