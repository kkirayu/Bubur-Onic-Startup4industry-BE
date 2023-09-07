import {AuthContext, TableLowcode} from 'alurkerja-ui'
import React, {useState} from 'react'
import ReactDOM from 'react-dom/client';
import 'alurkerja-ui/dist/style.css'
import {useParams} from "react-router-dom";


function BasicAlurkerjaTable({baseUrl, tableName, moduleName, specPath}) {
    const [pageConfig, setPageConfig] = useState({limit: 10, page: 0})
    const [renderState, setRenderState] = useState(0)
    const [filterBy, setFilterBy] = useState()
    const [search, setSearch] = useState()

    return (<>
            <div className='pt-10'>
                <AuthContext.Provider value={window.axios}>
                    <TableLowcode
                        baseUrl={baseUrl}
                        tableName={tableName}
                        moduleName={moduleName}
                        specPath={specPath}
                        renderState={renderState}
                        setRenderState={setRenderState}
                        pageConfig={pageConfig}
                        setPageConfig={setPageConfig}
                        filterBy={filterBy}
                        setFilterBy={setFilterBy}
                        search={search}
                        setSearch={setSearch}
                    />
                </AuthContext.Provider>
            </div>
        </>
    );
}

export default BasicAlurkerjaTable;

export const AlurkerjaCrudTable = () => {
    const params = useParams()

    const crudMenu = localStorage.getItem("crudMenu")

    var crudMenuJson = JSON.parse(crudMenu)

    const crudMenuJsonFiltered = Object.values(crudMenuJson).flat().filter((item) => item.table === params.tableName)[0]

    return <BasicAlurkerjaTable tableName={crudMenuJsonFiltered.label}
                                baseUrl={""}
                                specPath={crudMenuJsonFiltered.path}></BasicAlurkerjaTable>
}
export const AlurkerjaBpmnTable = () => {
    const params = useParams()

    const crudMenu = localStorage.getItem("bpmnMenu")

    const crudMenuJson = JSON.parse(crudMenu)

    const crudMenuJsonFiltered = crudMenuJson[params.tableName]
    return <BasicAlurkerjaTable tableName={crudMenuJsonFiltered.label}
                                baseUrl={""}
                                specPath={crudMenuJsonFiltered.path}></BasicAlurkerjaTable>
}
