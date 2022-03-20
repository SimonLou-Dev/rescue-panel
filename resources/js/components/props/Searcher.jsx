import React from 'react';

function Searcher(props) {

    return (
        <div className={'searcher'}>
            <img src={'/assets/images/search.png'} alt={''} className={'searcher-icon'}/>
            <input type={'text'} className={'searcher-input'} value={props.value} onChange={(e)=>{props.callback(e.target.value)}}/>
        </div>
 )
}

export default Searcher;
