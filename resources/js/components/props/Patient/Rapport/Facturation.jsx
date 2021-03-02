import React from 'react'

class Facturation extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            activate: null,
        }
    }

    componentDidMount() {
        this.setState({activate:this.props.payed});
    }


    onchange(e){
        if(this.state.activate){
            this.setState({activate:false})
            this.props.onPayedChange(false);
        }else{
            this.setState({activate:true})
            this.props.onPayedChange(true);
        }
    }

    render() {
        return(
            <div className={'Rapport-Card'}>
                <h1>Facturation</h1>
                <div className="Form-Group facture">
                    <input type="number" autoComplete={'off'} placeholder="montant en $" value={this.props.montant} onChange={(e)=> this.props.onMotantChange(e.target.value)}/>
                    <input id="facture_checkbox" className="switch" type="checkbox" checked={this.state.activate} onChange={(e)=>this.onchange(e)} />
                    <label htmlFor="facture_checkbox" id="switch">a</label>
                </div>
            </div>
        )
    };
}
export default Facturation
