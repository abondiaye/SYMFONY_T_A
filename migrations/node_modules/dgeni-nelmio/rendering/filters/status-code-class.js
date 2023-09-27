'use strict';

module.exports =  {
  name: 'statusCodeClass',
  process: function (statusCode) {
    var classes = ['label'];

    if (statusCode >= 200 && statusCode < 300)
      classes.push('label-success');
    else if (statusCode >= 300 && statusCode < 400)
      classes.push('label-warning');
    else if (statusCode >= 400)
      classes.push('label-danger');
    else
      classes.push('label-default');

    return classes.join(' ');
  }
};
