import React, {useEffect, useState} from 'react';
import PageNavigator from "../../props/PageNavigator";
import axios from "axios";
import UpdaterBtn from "../../props/UpdaterBtn";

function Logs(props) {
    const [pagination, setPagination] = useState([]);
    const [data, setData] = useState([]);
    const [page, setPage] = useState(1);

    useEffect(async () => {
        navigate();
    }, [])

    const navigate = async (newpage = page) => {
        if(newpage !== page){
            setPage(newpage);
        }
        await axios({
            method: 'GET',
            url: '/data/user/logs?page=' + newpage
        }).then(r => {
            setData(r.data.logs.data)
            setPagination(r.data.logs)
        })
    }


    return (<div className={'TablePage'}>
        <div className={'PageCenter'}>
            <div className={'table-header'}>
                <PageNavigator prev={()=> {navigate(page-1)}} next={()=> {navigate(page+1)}} prevDisabled={(pagination.prev_page_url === null)} nextDisabled={(pagination.next_page_url === null)}/>
                <input type={'text'} disabled={true} value={'nombre de ligne : ' + (pagination ? pagination.total : '') }/>
                <UpdaterBtn callback={navigate}/>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                        <tr>
                            <th>date</th>
                            <th>actions</th>
                            <th>user_id</th>
                            <th>infos</th>
                        </tr>
                    </thead>
                    <tbody>
                    {data && data.map((d)=>
                        <tr key={d.id}>
                            <td>{d.created_at}</td>
                            <td>{d.action}</td>
                            <td>{d.user_id}</td>
                            <td>{d.desc}</td>
                        </tr>
                    )}

                    </tbody>
                </table>
            </div>

        </div>

    </div> )
}

export default Logs;
