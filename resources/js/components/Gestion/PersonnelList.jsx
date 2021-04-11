import axios from 'axios';
import React from 'react';
import PersonnelLine from "../props/Gestion/Personnel/PersonnelLine";
import PagesTitle from "../props/utils/PagesTitle";
import TableBottom from "../props/utils/TableBottom";
import {Link} from "react-router-dom";
import PermsContext from "../context/PermsContext";

class PersonnelList extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            userlist: null,
            data:false
        }
        this.getdata = this.getdata.bind(this)
    }

    async componentDidMount(){
        this.getdata()
    }

    async getdata() {
        var req = await axios({
            url: '/data/users/getall',
            method: 'GET'
        })
        console.log(req)
        this.setState({
            userlist: req.data.users,
            data: true
        })
    }

    render() {
        const perm = this.context;
        if(this.state.data){
            return (
                <div className={"PersonnelList"}>
                    <section className={'header'}>
                        <PagesTitle title={'Liste du personnel'}/>
                        {perm.edit_perm === true &&
                            <Link to={'/gestion/perm'} className={'btn'}>gérer les permissions</Link>
                        }
                    </section>
                    <section className={'list-personnel'}>
                        <table>
                            <thead>
                            <tr>
                                <th className={'id'}>id</th>
                                <th className={'name'}>nom prénom</th>
                                <th className={'tel'}>n° de tel</th>
                                <th className={'compte'}>n° de compte</th>
                                <th className={'grade'}>grade</th>
                                <th className={'pilote'}>pilote</th>
                            </tr>
                            </thead>
                            <tbody>
                                {this.state.userlist && this.state.userlist.map((user)=>
                                        <PersonnelLine id={user.id} key={user.id} pilote={user.pilote} name={user.name} compte={user.compte} tel={user.tel} grade={user.grade_id} update={this.getdata}/>
                                )}
                            </tbody>
                        </table>
                    </section>
                </div>
            )
        }else{
            return(
                <div className={'load'}>
                    <img src={'/assets/images/loading.svg'} alt={''}/>
                </div>
            )
        }

    }
}
PersonnelList.contextType = PermsContext;
export default PersonnelList;
