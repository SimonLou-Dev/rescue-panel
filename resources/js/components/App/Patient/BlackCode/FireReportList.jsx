import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import PageNavigator from "../../../props/PageNavigator";
import Searcher from "../../../props/Searcher";
import UpdaterBtn from "../../../props/UpdaterBtn";
import SwitchBtn from "../../../props/SwitchBtn";

function FireReportList(props){
    const [paginate, setPagination]= useState([]);
    const [page, setPage] = useState(1);
    const [search, setSearch] = useState("");
    const [data, setData] = useState([]);

    const RapportList = async (a = search , c = page) => {
        if(c !== page){
            setPage(c);
        }
        if(a !== search){
            setSearch(a);
            c = 1;
            setPage(1);
        }
        await axios({
            url : '/data/arson/get' +'?query='+a+'&page='+c,
            method: 'GET'
        }).then(r => {
            setPagination(r.data.reports)
            setData(r.data.reports.data)

        })

    }

    useEffect(()=>{
        RapportList();
    },[])

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (
        <div className={'TablePage'}>
            <div className={'PageCenter'}>
                <div className={'table-header'}>
                    <PageNavigator prev={()=> {RapportList(search,page-1)}} next={()=> {RapportList(search,page+1)}} prevDisabled={(paginate.prev_page_url === null)} nextDisabled={(paginate.next_page_url === null)}/>
                    <Searcher value={search} callback={(v) => {RapportList(v)}}/>
                    <UpdaterBtn callback={RapportList}/>
                </div>
                <div className={'table-container'}>
                    <table>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>black code</th>
                            <th>propriété</th>
                            <th>type</th>
                            <th>pdf</th>
                            <th>date</th>
                        </tr>
                        </thead>
                        <tbody>
                        {data && data.map((u)=>
                            <tr key={u.id}>
                                <td>{u.id}</td>
                                <td onClick={()=>{Redirection('/blackcodes/fire/'+u.get_b_c.id)}} className={'link'}>{u.get_b_c.id}</td>
                                <td>{u.property_number} ({u.compte})</td>
                                <td>{u.get_type.name}</td>
                                <td><a href={'/pdf/arson/'+u.id} target={"_blank"} className={'btn'}><img src={'/assets/images/pdf.png'} alt={''}/></a></td>
                                <td>{u.created_at}</td>
                            </tr>
                        )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    )
}
export default FireReportList;
