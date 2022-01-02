import React, {useEffect} from 'react';
import axios from "axios";
import Echo from 'laravel-echo';
import GetInfos from "../AuthComponent/GetInfos";
import {Route} from "react-router-dom";
import Maintenance from "../Maintenance";


function Layout(props) {
    useEffect(async ()=>{
       Pusher.logToConsole = true;
        let pusher = new Pusher('fd78f74e8faecbd2405b', {
            cluster: 'eu'
        });
        let userChan = pusher.subscribe('User.'+env+'.1');
        userChan.bind('notify', (data)=>{console.log(data)})


    }, [])



    return (
        <div className={"test"}>

            <Route path='/maintenance' component={Maintenance}/>
        </div>
    )
}

export default Layout;
