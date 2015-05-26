var ReactTools = require('react-tools');
var babel = require('babel-jest').process;
module.exports = {
    process: function(src, filename) {
        if (!filename.match(/\.jsx$/)) {
            return '';
        }
        return ReactTools.transform(babel(src, filename));
    }
};