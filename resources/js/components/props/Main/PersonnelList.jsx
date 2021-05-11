import React from 'react';
import axios from 'axios';
import PersonnelCard from "./PersonnelCard";

class PersonnelList extends React.Component{

    constructor(props){
        super(props);
        this.state = {date: new Date(), users: [],data:false};
    }
    componentDidMount() {
        this.request();
        this.timerID = setInterval(
            () => this.tick(),
            10*60*1000
        );
    }
    componentWillUnmount() {
        clearInterval(this.timerID);
    }
    async tick() {
        this.request();
        this.setState({
            date: new Date()
        });
    }

    async request(){
        this.hasdata(false);
        var req = await axios({
            url: '/data/AllInService',
            method: 'GET',
        });
        this.setState({users: req.data.users, data:true})

    }
    hasdata(bool){
            this.setState({data:bool})
        }

    render(){
        if(this.state.data === true){
            return(
                <div className={'Personnel_service'}>
                    <h1>Personnel en service : </h1>
                    <div className={'Personnel-list'}>
                        {this.state.users.map((user)=>
                            <PersonnelCard key={user.id} name={user.name} color={'#eb34eb'}/>
                        )}
                    </div>
                    <div className={'bottom'}>
                        <div className={'groupcard'}>
                            <div className={'contain'}>
                                <div className={'tag'}>
                                    <label>Formations</label>
                                    <div style={{backgroundColor: '#eb34eb'}}/>
                                </div>
                                <div className={'tag'}>
                                    <label>Formations</label>
                                    <div style={{backgroundColor: '#eb34eb'}}/>
                                </div>
                                <div className={'tag'}>
                                    <label>Formations</label>
                                    <div style={{backgroundColor: '#eb34eb'}}/>
                                </div>
                                <div className={'tag'}>
                                    <label>Formations</label>
                                    <div style={{backgroundColor: '#eb34eb'}}/>
                                </div>
                                <div className={'tag'}>
                                    <label>Formations</label>
                                    <div style={{backgroundColor: '#eb34eb'}}/>
                                </div>
                                <div className={'tag'}>
                                    <label>Formations</label>
                                    <div style={{backgroundColor: '#eb34eb'}}/>
                                </div>
                                <div className={'tag'}>
                                    <label>Formations</label>
                                    <div style={{backgroundColor: '#eb34eb'}}/>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            );

        }else{
            return(
                <div className={'Personnel_service'}>
                    <h1>Personnel en service : </h1>
                    <div className={'Personnel-list'}>
                        {!this.state.data &&
                        <div className={'load'}>
                            <img src={'/assets/images/loading.svg'} alt={''}/>
                        </div>
                        }
                    </div>
                </div>
            );
        }
    }
}
export default PersonnelList;
