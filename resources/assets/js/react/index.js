import React from 'react';
import ReactDOM from 'react-dom';
import Shortcodes from './Shortcodes';
import Reports from './Reports';
import BanList from './BanList';

$(function() {
    if(window.shortcodesData !== undefined)
        ReactDOM.render(<Shortcodes shortcodes={window.shortcodesData} />, document.getElementById("shortcodes"));
    if(window.bppData !== undefined)
        ReactDOM.render(<BanList banlist={window.bppData} />, document.getElementById("banlist"));
    if(window.reportsData !== undefined)
        ReactDOM.render(<Reports reports={window.reportsData} entity={window.reportsEntity} />, document.getElementById("reports"));
});