import React from 'react';
import {useState, useEffect, useContext, useReducer} from 'react'
import axios from "axios";
import PersonnelCard from "./PersonnelCard";
import PermsContext from "../../context/PermsContext";
import useForceUpdate from 'use-force-update';

export function ListeServicePersonnel()  {
    const perm = useContext(PermsContext)
    const [select, setSelected] = useState(perm.user_id)
    const forceUpdate = useForceUpdate();
    const [data, setData] = useState(false)
    const [usersSates, setUsersStates] = useState([])
    const [displayed, setDisplayed] = useState([])


    useEffect(() => {
        request();

    }, [])

    const mounted = () => {
        if(select == undefined){
            setSelected(perm.user_id)
            forceUpdate()
        }
    }

    const clickede = (id) => {
        if(id === perm.user_id){
            setSelected(id)
        }else if(perm.service_modify){
            setSelected(id)
        }
        forceUpdate()

    }

    const request = async () => {
        setData(false)
        await axios({
            url: '/data/AllInService',
            method: 'GET',
        }).then(response => {
            setUsersStates([response.data.users, response.data.states])
            setDisplayed(response.data.userStates)
            setData(true)
        })
        mounted()
    }

    if(data){
        return (
        <div className={'Personnel_service'}>
            <h1>Personnel en service : </h1>
            <div className={'service--list'}>
                <div className={'Personnel-list'}>
                    <div className={'tags-list'}>
                    {usersSates[0] && usersSates[0].map((user)=>
                        <PersonnelCard key={user.id} name={user.name} clicked={(id)=>{clickede(id)}} user={user} states={usersSates[1]} update={request} selected={select === user.id}/>
                    )}

                    </div>
                </div>
                <div className={'left'}>
                    <div className={'groupcard'}>
                        <div className={'contain'}>
                            {data === true &&
                            usersSates[1].length > 0 &&
                            usersSates[1].map((item)=>
                                <div className={'tag'} key={item.id} onClick={async () => {
                                    if(select !== undefined){
                                        await axios({
                                            method: 'PUT',
                                            url: '/data/user/' + select + '/changestate/' + item.id,
                                        }).then(() => {
                                            request()
                                        })
                                    }
                                }}>
                                    <label>{item.name}</label>
                                    <div style={{backgroundColor:item.color}}/>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        )
    }else{
        return (
            <div className={'Personnel_service'}>
                <h1>Personnel en service : </h1>
                <div className={'Personnel-list'}>
                    {!data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                    }
                </div>
            </div>
        )
    }
}



