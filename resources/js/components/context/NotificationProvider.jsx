import React, {useReducer, useState, createContext, useContext} from "react";
import {v4} from 'uuid';
import Notification from "../props/utils/Notification";
import NotificationContext from "./NotificationContext";


const NotificationsProvider = (props) => {

    const [state, dispatch] = useReducer((state, action) => {
        switch (action.type){
            case 'ADD_NOTIFICATION':
                let mypayload = action.payload[0].payload;
                return [...state, {
                    id: mypayload.id,
                    type: mypayload.type,
                    message: mypayload.message
                }];
            case 'REMOVE_NOTIFICATION':
                return state.filter(el => el.id !== action.id);
            default :
                return state;
        }
    },[
        {}
    ]);


    return (
        <NotificationContext.Provider value={dispatch}>
            <div className={'notification-wrapper'}>
                {state.map(note => {
                    return <Notification dispatch={dispatch} key={note.id} {...note}/>
                })}
            </div>
            {props.children}
        </NotificationContext.Provider>
    )
};

export const useNotifications = () => {
    const dispatch = useContext(NotificationContext)

    return (...props)=>{
        dispatch({
            type: 'ADD_NOTIFICATION',
            payload: {
                ...props
            }
        })
    };
}

export default NotificationsProvider;
