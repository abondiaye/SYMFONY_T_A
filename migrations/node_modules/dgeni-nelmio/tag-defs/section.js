module.exports = function () {
  return {
    name: 'section',
    transforms: function (doc, tag, value) {
      if (!value) return;
      
      return value.toLowerCase();
    }
  };
};