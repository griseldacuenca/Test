//
//  CalculatorBrain.swift
//  calculator
//
//  Created by Griselda Cuenca on 8/11/16.
//  Copyright © 2016 Griselda Cuenca. All rights reserved.
//

import Foundation

class CalculatorBrain {
    
    init() {
        //Here we can put this
        func learnOp(op: Op) {
            knownOps[op.description] = op
        }
        learnOp(Op.BinaryOperation("×", *))
        learnOp(Op.BinaryOperation("+", +))
        learnOp(Op.BinaryOperation("−", {$1 - $0}))
        learnOp(Op.BinaryOperation("÷", {$1 / $0}))
        learnOp(Op.UnaryOperation("√", sqrt))
        
        //Instead of this:
//        knownOps["×"] = Op.BinaryOperation("×", *)
//        knownOps["+"] = Op.BinaryOperation("+", +)
//        knownOps["−"] = Op.BinaryOperation("−"){$1 - $0}
//        knownOps["÷"] = Op.BinaryOperation("÷"){$1 / $0}
//        knownOps["√"] = Op.UnaryOperation("√", sqrt)
    }
    
    // This enum implements the Printable protocol
    private enum Op: CustomStringConvertible
    {
        case Operand(Double)
        case UnaryOperation(String, Double -> Double)
        case BinaryOperation(String,(Double,Double)->Double)
        
        var description: String {
            get {
                switch self {
                case .Operand(let operand):
                    return "\(operand)"
                case .UnaryOperation(let symbol, _):
                    return symbol
                case .BinaryOperation(let symbol, _):
                    return symbol
                }
            }
        }
        
    }
    
    private var opStack = [Op]()
    private var knownOps = [String:Op]()
    
    func pushOperand(operand: Double) -> Double? {
        opStack.append(Op.Operand(operand))
        return evaluate()
    }
    
    func performOperation(symbol: String) -> Double? {
        if let operation = knownOps[symbol] {
            opStack.append(operation)
        }
        return evaluate()
    }
    
    func evaluate()->Double? {
        let (result, reminder) = evaluate(opStack)
        print("\(opStack) = \(result) with \(reminder) left over")
        return(result)
    }
    
    private func evaluate(ops: [Op]) -> (result: Double?, remainingAllOps: [Op]) {
        if !ops.isEmpty {
            var remainingOps = ops
            let op = remainingOps.removeLast()
            switch op {
            case .Operand(let operand):
                return(operand, remainingOps)
            case .UnaryOperation(_, let operation):
                let operandEvaluation = evaluate(remainingOps)
                if let operand = operandEvaluation.result {
                    return(operation(operand), operandEvaluation.remainingAllOps)
                }
            case .BinaryOperation(_, let operation):
                let operandEvaluation1 = evaluate(remainingOps)
                if let operand1 = operandEvaluation1.result {
                    let operandEvaluation2 = evaluate(operandEvaluation1.remainingAllOps)
                    if let operand2 = operandEvaluation2.result {
                        return (operation(operand1, operand2),operandEvaluation2.remainingAllOps)
                    }
                }
            }
        }
        return (nil, ops)
    }
    
}