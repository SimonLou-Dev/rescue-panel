import React from 'react';
import axios from 'axios';
import PersonnelCard from "./PersonnelCard";

class PersonnelList extends React.Component{

    constructor(props){
        super(props);
        this.state = {date: new Date(), users: [],states: [], displayed: [], data:false};
        this.request= this.request.bind(this);
    }
    componentDidMount() {
        this.request();
        this.timerID = setInterval(
            () => this.tick(),
            2*60*1000
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
        this.setState({users: req.data.users, states: req.data.states, displayed:req.data.userStates, data:true})

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
                            <PersonnelCard key={user.id} name={user.name} user={user} states={this.state.states} update={this.request}/>
                        )}
                    </div>
                    <div className={'bottom'}>
                        <div className={'groupcard'}>
                            <div className={'contain'}>
                                {this.state.data === true &&
                                    this.state.displayed.length > 0 &&
                                        this.state.displayed.map((item)=>
                                            <div className={'tag'} key={item.id}>
                                            <label>{item.name}</label>
                                            <div style={{backgroundColor:item.color}}/>
                                        </div>
                                    )}
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
