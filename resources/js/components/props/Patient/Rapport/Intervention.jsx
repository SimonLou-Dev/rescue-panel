import React from 'react'
import axios from "axios";

class Intervention extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            transportlist: "",
            interlist: ""
        }
    }

    async componentDidMount() {
        var req = await axios({
            url: '/data/rapport/getforinter',
            method: 'GET'
        });
        this.setState({transportlist: req.data.transport, interlist: req.data.intertype})
    }

    render() {
        return(
            <div className={'Rapport-Card Intervention'}>
                <h1>Intervention</h1>
                <div className="Form-Group">
                    <select value={this.props.type} onChange={(e)=> this.props.onTypeChange(e.target.value)}>
                        <option disabled value={0}>type d'intervention</option>
                        {this.state.interlist !== "" &&
                            this.state.interlist.map((inter)=>
                                <option key={inter.id} value={inter.id}>{inter.name}</option>
                            )
                        }
                    </select>
                    <select value={this.props.transport} onChange={(e)=> this.props.onTransportChange(e.target.value)}>
                        {this.state.transportlist !== "" &&
                            this.state.transportlist.map((broum)=>
                                <option key={broum.id} value={broum.id}> transport : {broum.name}</option>
                            )
                        }
                    </select>
                </div>
                <div className="From-Group description">
                    <label>Description</label>
                    <textarea autoComplete={'off'} rows='4' maxLength={"255"} onChange={(e)=> this.props.onDescChange(e.target.value)} value={this.props.description}/>
                </div>
            </div>
        )
    }
}
export default Intervention
