import React from 'react';
import axios from "axios";


class BugRepport extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            text:'',
        }
        this.submit = this.submit.bind(this)
    }
    async submit(e) {
        e.preventDefault();
        let req = await axios({
            url: '/data/bug',
            method:'POST',
            data:{
                text: this.state.text
            }
        })
        if(req.status === 201){
            this.props.close();
        }
    }

    render() {
        return (
            <div className={'BugRepport'}>
                <div className={'Repport-Card'}>
                    <h1>Signaler un bug</h1>
                    <form onSubmit={this.submit}>
                        <label>Description : </label>
                        <textarea value={this.state.text} onChange={(e)=>{this.setState({text:e.target.value})}}/>
                        <div className="rowed">
                            <button className={'btn'} onClick={()=>this.props.close()}>Fermer</button>
                            <button className={'btn'} type={'submit'}>Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        )
    }
}

export default BugRepport;
