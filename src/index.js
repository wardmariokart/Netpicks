/* const { EnvironmentPlugin } = require('webpack');
 */
require('./style.css');
import {extraQuestionsInit} from './js/extraQuestions.js';
import {setupDetailPage} from './js/detail.js';

{
  const init = () => {
    extraQuestionsInit();
    setupDetailPage();
  };

  init();
}
