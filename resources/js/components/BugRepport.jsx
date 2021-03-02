import React from 'react';
import axios from "axios";


class BugRepport extends React.Component {
    constructor(props) {
        super(props);
        this.state = {}
    }

    render() {
        return (
            <div className={'BugRepport'}>
                <div className={'Repport-Card'}>
                    <h1>Signaler un bug</h1>
                    <form>
                        <label>Description : </label>
                        <textarea/>
                        <button className={'btn'} type={'submit'}>Envoyer</button>
                    </form>
                </div>
            </div>
        )
    }
}

export default BugRepport;
