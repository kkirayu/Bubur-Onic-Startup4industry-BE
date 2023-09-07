import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import {AuthContext} from "alurkerja-ui";
import {AlurkerjaBpmnTable, AlurkerjaCrudTable,} from "./components/BasicAlurkerjaTable";
function MainApp() {
    return (

        <AuthContext.Provider value={window.axios}>
        <Routes>
            <Route path="/app/crud/:tableName" element={<AlurkerjaCrudTable/> } />
            <Route path="/app/bpmn/:tableName" element={<AlurkerjaBpmnTable/> } />
        </Routes>
        </AuthContext.Provider>
    );
}
export default MainApp;
if (document.getElementById('alurkerjaapp')) {
    ReactDOM.render(
        <BrowserRouter>
            <MainApp />
        </BrowserRouter>
        , document.getElementById('alurkerjaapp'));
}
