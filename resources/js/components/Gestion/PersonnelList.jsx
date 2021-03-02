import axios from 'axios';
import React from 'react';
import PersonnelLine from "../props/Gestion/Personnel/PersonnelLine";

class PersonnelList extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            userlist: null,
            data:false
        }
    }

    async componentDidMount(){
        var req = await axios({
            url: '/data/users/getall',
            method: 'GET'
        })
        console.log(req)
        this.setState({
            userlist: req.data.users,
            data:true
        })
    }

    render() {
        if(this.state.data){
        return (
                    <div className={"PersonnelList"}>
                        <section className={'header'}>
                            <div className={'title-contain'}>
                                <h1>Personnel</h1>
                            </div>
                        </section>
                        <section className={'list-personnel'}>
                            <table>
                                <thead>
                                    <tr>
                                        <th className={'id'}>id</th>
                                        <th className={'name'}>nom prénom</th>
                                        <th className={'grade'}>grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {this.state.userlist &&
                                        this.state.userlist.map((user)=>
                                            user.grade < 10 &&
                                                <PersonnelLine id={user.id} key={user.id} name={user.name} grade={user.grade} update={this.componentDidMount}/>
                                        )

                                    }
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

    };
}

export default PersonnelList;
