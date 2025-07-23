wp.blocks.registerBlockType('lc/time-to-read', {
  edit: function () {
    return wp.element.createElement(
      'p',
      null,
      'Reading time will be rendered on the front end.'
    );
  },
  save: function () {
    return null; // Server-rendered
  },
});