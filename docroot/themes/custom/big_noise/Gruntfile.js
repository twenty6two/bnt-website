module.exports = function (grunt) {

  grunt.initConfig({
    compass: {
      config: 'config.rb'
    }
  });

  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('build', [
    'compass'
  ]);
};